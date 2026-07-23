/**
 * Ponte Vue 2 -> Vue 3 (CDN / sem bundler).
 * Mantém new Vue({ el }), Vue.component, Vue.use e aliases de lifecycle.
 * Carregar imediatamente após o Vue 3 global build.
 *
 * Importante: o compilador runtime do Vue 3 (templates no DOM) resolve
 * helpers como openBlock em window.Vue — por isso todos os exports
 * do build original são copiados para o wrapper.
 */
(function () {
  var Vue3 = window.Vue;
  if (!Vue3 || typeof Vue3.createApp !== 'function') {
    console.error('[vue3-bridge] Vue 3 global build não encontrado.');
    return;
  }

  var registry = {
    components: Object.create(null),
    directives: Object.create(null),
    plugins: []
  };

  function normalizeOptions(options) {
    if (!options || typeof options !== 'object') {
      return {};
    }

    var opts = Object.assign({}, options);

    if (opts.data && typeof opts.data !== 'function') {
      var dataObj = opts.data;
      opts.data = function () {
        return dataObj;
      };
    }

    if (opts.beforeDestroy && !opts.beforeUnmount) {
      opts.beforeUnmount = opts.beforeDestroy;
      delete opts.beforeDestroy;
    }

    if (opts.destroyed && !opts.unmounted) {
      opts.unmounted = opts.destroyed;
      delete opts.destroyed;
    }

    return opts;
  }

  function applyRegistry(app) {
    Object.keys(registry.components).forEach(function (name) {
      app.component(name, registry.components[name]);
    });
    Object.keys(registry.directives).forEach(function (name) {
      app.directive(name, registry.directives[name]);
    });
    registry.plugins.forEach(function (entry) {
      try {
        app.use(entry[0], entry[1]);
      } catch (err) {
        console.warn('[vue3-bridge] plugin falhou no app.use:', err);
      }
    });
    return app;
  }

  function createConfiguredApp(options) {
    var app = Vue3.createApp(normalizeOptions(options));
    return applyRegistry(app);
  }

  function VueCompat(options) {
    var app = createConfiguredApp(options || {});
    if (options && options.el) {
      return app.mount(options.el);
    }
    return app;
  }

  // Copia helpers/runtime do Vue 3 (openBlock, createVNode, compile, etc.)
  Object.keys(Vue3).forEach(function (key) {
    VueCompat[key] = Vue3[key];
  });

  // APIs compatíveis que precisam sobrescrever os exports originais
  VueCompat.createApp = createConfiguredApp;
  VueCompat.config = Vue3.config || {
    productionTip: false,
    silent: false,
    globalProperties: {}
  };

  VueCompat.component = function (name, definition) {
    if (definition === undefined) {
      return registry.components[name];
    }
    registry.components[name] = definition;
    return VueCompat;
  };

  VueCompat.directive = function (name, definition) {
    if (definition === undefined) {
      return registry.directives[name];
    }
    registry.directives[name] = definition;
    return VueCompat;
  };

  VueCompat.use = function (plugin, options) {
    registry.plugins.push([plugin, options]);

    var fakeApp = {
      component: VueCompat.component,
      directive: VueCompat.directive,
      mixin: function () { return fakeApp; },
      provide: function () { return fakeApp; },
      config: VueCompat.config,
      version: VueCompat.version,
      use: VueCompat.use
    };

    try {
      if (plugin && typeof plugin.install === 'function') {
        plugin.install(fakeApp, options);
      } else if (typeof plugin === 'function') {
        plugin(fakeApp, options);
      }
    } catch (err) {
      console.warn('[vue3-bridge] plugin.install:', err);
    }

    return VueCompat;
  };

  VueCompat.prototype = {};
  VueCompat.extend = function () {
    throw new Error('[vue3-bridge] Vue.extend não é suportado. Use componentes em objeto.');
  };

  window.Vue3 = Vue3;
  window.Vue = VueCompat;
})();
