import { motion } from 'framer-motion';
import { Sparkles, MessageCircle, Send } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useState } from 'react';

export const AIRecommendationSection = () => {
  const [query, setQuery] = useState('');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Future AI integration
    console.log('AI Query:', query);
  };

  const suggestedPrompts = [
    "Eventos gratuitos para o fim de semana",
    "Shows de música ao ar livre",
    "Exposições de arte para toda família",
    "Teatro clássico esta semana",
  ];

  return (
    <section className="py-16 md:py-24 relative overflow-hidden">
      {/* Background decoration */}
      <div className="absolute inset-0 bg-gradient-to-b from-secondary/30 via-primary/5 to-secondary/30" />
      <div className="absolute top-0 left-1/4 w-96 h-96 bg-primary/10 rounded-full blur-3xl" />
      <div className="absolute bottom-0 right-1/4 w-96 h-96 bg-accent/10 rounded-full blur-3xl" />
      
      <div className="container px-4 relative z-10">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5 }}
          className="max-w-3xl mx-auto text-center"
        >
          {/* Header */}
          <div className="inline-flex items-center gap-2 bg-primary/10 border border-primary/20 rounded-full px-4 py-2 mb-6">
            <Sparkles className="h-4 w-4 text-primary" />
            <span className="text-sm font-medium text-primary">Em breve</span>
          </div>
          
          <h2 className="font-heading text-3xl md:text-4xl font-bold text-foreground mb-4">
            Encontre o evento perfeito com
            <span className="text-transparent bg-clip-text bg-gradient-to-r from-primary to-accent"> Inteligência Artificial</span>
          </h2>
          
          <p className="text-muted-foreground text-lg mb-8 max-w-2xl mx-auto">
            Nossa IA vai analisar seus interesses e sugerir os melhores eventos culturais para você. 
            Diga o que procura e descubra experiências incríveis.
          </p>

          {/* Chat-like interface preview */}
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="bg-card border border-border rounded-2xl p-6 shadow-lg shadow-black/10"
          >
            {/* Example conversation */}
            <div className="space-y-4 mb-6">
              <div className="flex items-start gap-3">
                <div className="w-8 h-8 rounded-full bg-secondary flex items-center justify-center flex-shrink-0">
                  <MessageCircle className="h-4 w-4 text-muted-foreground" />
                </div>
                <div className="bg-secondary/50 rounded-2xl rounded-tl-sm px-4 py-3 text-left">
                  <p className="text-sm text-muted-foreground">
                    Olá! Procuro algo cultural para fazer com minha família no domingo...
                  </p>
                </div>
              </div>
              
              <div className="flex items-start gap-3 justify-end">
                <div className="bg-primary/10 border border-primary/20 rounded-2xl rounded-tr-sm px-4 py-3 text-left max-w-md">
                  <p className="text-sm text-foreground">
                    Ótimo! Encontrei <span className="text-primary font-medium">3 eventos perfeitos</span> para sua família no domingo: 
                    uma exposição de arte interativa no MASP, feira literária gratuita na Biblioteca Central, e... 
                  </p>
                </div>
                <div className="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center flex-shrink-0">
                  <Sparkles className="h-4 w-4 text-primary-foreground" />
                </div>
              </div>
            </div>

            {/* Input preview */}
            <form onSubmit={handleSubmit} className="relative">
              <Input
                placeholder="Descreva o tipo de evento que você procura..."
                value={query}
                onChange={(e) => setQuery(e.target.value)}
                className="pr-12 h-12 bg-secondary/50 border-border focus:border-primary/50 rounded-xl"
                disabled
              />
              <Button 
                type="submit" 
                size="icon" 
                className="absolute right-2 top-1/2 -translate-y-1/2 h-8 w-8 rounded-lg"
                disabled
              >
                <Send className="h-4 w-4" />
              </Button>
            </form>

            {/* Suggested prompts */}
            <div className="mt-4 flex flex-wrap gap-2 justify-center">
              {suggestedPrompts.map((prompt, index) => (
                <button
                  key={index}
                  className="text-xs bg-secondary/50 hover:bg-secondary text-muted-foreground hover:text-foreground px-3 py-1.5 rounded-full border border-border hover:border-primary/30 transition-colors cursor-not-allowed opacity-70"
                  disabled
                >
                  {prompt}
                </button>
              ))}
            </div>

            {/* Coming soon overlay hint */}
            <div className="mt-6 pt-4 border-t border-border/50">
              <p className="text-xs text-muted-foreground">
                🚀 Esta funcionalidade está em desenvolvimento. Em breve você poderá conversar com nossa IA!
              </p>
            </div>
          </motion.div>

          {/* Features preview */}
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.3 }}
            className="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8"
          >
            {[
              { icon: '🎯', title: 'Recomendações Personalizadas', desc: 'Eventos baseados nos seus gostos' },
              { icon: '💬', title: 'Conversa Natural', desc: 'Pergunte do seu jeito' },
              { icon: '📅', title: 'Planejamento Fácil', desc: 'Organize sua agenda cultural' },
            ].map((feature, index) => (
              <div 
                key={index}
                className="bg-card/50 border border-border/50 rounded-xl p-4 text-center"
              >
                <span className="text-2xl mb-2 block">{feature.icon}</span>
                <h3 className="font-semibold text-foreground text-sm mb-1">{feature.title}</h3>
                <p className="text-xs text-muted-foreground">{feature.desc}</p>
              </div>
            ))}
          </motion.div>
        </motion.div>
      </div>
    </section>
  );
};
