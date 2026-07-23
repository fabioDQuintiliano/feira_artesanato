<!--[CONTAINER-layout_react]-->

<div id="app_chat" v-cloak class="min-h-screen bg-background flex flex-col">
    <!-- Header Minimalista -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-background/95 backdrop-blur-md border-b border-border/50">
        <div class="container px-4">
            <div class="flex items-center justify-between h-16">
                <a href="<?= ROOT ?>"
                    class="flex items-center gap-2 text-sm font-medium text-muted-foreground hover:text-primary transition-colors">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                    Voltar para Home
                </a>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center">
                        <i data-lucide="bot" class="h-4 w-4 text-primary"></i>
                    </div>
                    <span class="font-display font-semibold text-foreground">Guia Cultural</span>
                </div>
                <div class="w-20"></div> <!-- Spacer para centralizar o título -->
            </div>
        </div>
    </header>

    <!-- Chat Area -->
    <main class="flex-1 pt-20 pb-32">
        <div class="container max-w-3xl px-4">
            <!-- Mensagens -->
            <div class="space-y-6 py-8">
                <!-- Welcome Message -->
                <div class="flex gap-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0">
                        <i data-lucide="bot" class="h-5 w-5 text-primary"></i>
                    </div>
                    <div class="flex-1 space-y-4">
                        <div class="bg-card border border-border/50 rounded-2xl rounded-tl-none p-4 shadow-sm">
                            <p class="text-foreground leading-relaxed">
                                Olá! Sou o **Guia Cultural** da sua cidade. ✨
                                <br><br>
                                Estou aqui para ajudar você a encontrar os melhores eventos. O que você está procurando?
                                Pode me contar seus gostos, se está com crianças, ou se quer algo mais agitado!
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Messages -->
                <div v-for="(msg, index) in mensagens" :key="index" class="flex gap-4"
                    :class="{'flex-row-reverse': msg.role === 'user'}">
                    <!-- Avatar -->
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                        :class="msg.role === 'user' ? 'bg-secondary border border-border' : 'bg-primary/10 border border-primary/20'">
                        <i v-if="msg.role === 'user'" data-lucide="user" class="h-5 w-5 text-muted-foreground"></i>
                        <i v-else data-lucide="bot" class="h-5 w-5 text-primary"></i>
                    </div>

                    <!-- Bubble -->
                    <div class="flex-1 max-w-[85%]">
                        <div class="p-4 shadow-sm"
                            :class="msg.role === 'user' ? 'bg-primary text-primary-foreground rounded-2xl rounded-tr-none' : 'bg-card border border-border/50 rounded-2xl rounded-tl-none text-foreground'">
                            <div class="prose prose-invert prose-sm max-w-none" v-html="formatarTexto(msg.text)"></div>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="carregando" class="flex gap-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0">
                        <i data-lucide="bot" class="h-5 w-5 text-primary"></i>
                    </div>
                    <div class="bg-card border border-border/50 rounded-2xl rounded-tl-none p-4 shadow-sm">
                        <div class="flex gap-1">
                            <span class="w-2 h-2 bg-primary/40 rounded-full animate-bounce"></span>
                            <span
                                class="w-2 h-2 bg-primary/40 rounded-full animate-bounce [animation-delay:0.2s]"></span>
                            <span
                                class="w-2 h-2 bg-primary/40 rounded-full animate-bounce [animation-delay:0.4s]"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Fixed Input Bar -->
    <div
        class="fixed bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-background via-background to-transparent pointer-events-none">
        <div class="container max-w-3xl px-0 pointer-events-auto">
            <div class="glass-effect rounded-2xl p-2 flex items-center gap-2 shadow-2xl shadow-primary/10">
                <input type="text" v-model="inputUsuario" @keyup.enter="enviarMensagem" ref="inputMensagem"
                    placeholder="Ex: Sugira um evento de rock com entrada gratuita..."
                    class="flex-1 bg-transparent border-none focus:ring-0 text-foreground placeholder:text-muted-foreground px-4 py-3"
                    :disabled="carregando" />
                <button @click="enviarMensagem"
                    class="w-12 h-12 rounded-xl bg-primary hover:bg-primary/90 text-primary-foreground flex items-center justify-center transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="carregando || !inputUsuario.trim()">
                    <i data-lucide="send" class="h-5 w-5"></i>
                </button>
            </div>
            <p
                class="text-[10px] text-center text-muted-foreground mt-3 uppercase tracking-widest font-bold opacity-50">
                Powered by Gemini AI • Informações Reais do Sistema
            </p>
        </div>
    </div>
</div>