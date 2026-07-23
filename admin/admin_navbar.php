<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="false">
  <div class="container-fluid py-1 px-3">
    <div class="d-flex align-items-center me-3">
      <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav" aria-label="Mostrar ou ocultar menu" title="Menu">
        <div class="sidenav-toggler-inner">
          <i class="sidenav-toggler-line"></i>
          <i class="sidenav-toggler-line"></i>
          <i class="sidenav-toggler-line"></i>
        </div>
      </a>
    </div>

    <nav aria-label="breadcrumb" class="me-auto">
      <?php if (!empty($configTableList->nome)): ?>
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm">
          <a class="opacity-5 text-dark" href="ROOT/admin">Painel</a>
        </li>
        <?php if (!empty($configTableList->menu)): ?>
        <li class="breadcrumb-item text-sm">
          <span class="opacity-5 text-dark"><?php echo htmlspecialchars($configTableList->menu, ENT_QUOTES, 'UTF-8'); ?></span>
        </li>
        <?php endif; ?>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
          <?php echo htmlspecialchars($configTableList->nome, ENT_QUOTES, 'UTF-8'); ?>
        </li>
      </ol>
      <h6 class="font-weight-bolder mb-0 mt-1"><?php echo htmlspecialchars($configTableList->nome, ENT_QUOTES, 'UTF-8'); ?></h6>
      <?php else: ?>
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0">
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Início</li>
      </ol>
      <h6 class="font-weight-bolder mb-0 mt-1"><?= PROJETO_NOME ?></h6>
      <?php endif; ?>
    </nav>

    <ul class="navbar-nav justify-content-end flex-row">
      <li class="nav-item d-flex align-items-center">
        <a href="ROOT/adm-logout" class="nav-link text-body font-weight-bold px-0">
          <i class="fa fa-sign-out-alt me-sm-1"></i>
          <span class="d-sm-inline d-none">Sair</span>
        </a>
      </li>
    </ul>
  </div>
</nav>
