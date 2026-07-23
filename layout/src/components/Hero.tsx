import { motion } from 'framer-motion';
import { Calendar, Sparkles } from 'lucide-react';
import { Button } from '@/components/ui/button';

export const Hero = () => {
  return (
    <section className="relative min-h-[70vh] flex items-center justify-center overflow-hidden bg-gradient-to-b from-background via-background to-secondary/30">
      {/* Background decorations */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <div className="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-primary/10 blur-3xl" />
        <div className="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-accent/10 blur-3xl" />
        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full bg-primary/5 blur-3xl" />
      </div>
      
      <div className="container relative z-10 px-4 py-20 md:py-32">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.8 }}
          className="max-w-4xl mx-auto text-center"
        >
          <motion.div
            initial={{ opacity: 0, scale: 0.9 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/30 mb-8"
          >
            <Sparkles className="h-4 w-4 text-primary" />
            <span className="text-sm font-medium text-primary">
              Descubra eventos incríveis
            </span>
          </motion.div>
          
          <h1 className="font-display text-4xl sm:text-5xl md:text-6xl lg:text-7xl mb-6 tracking-tight text-foreground">
            Sua{' '}
            <span className="text-gradient">
              <span className="font-semibold">Agenda Cultural</span>
            </span>
            <br />
            em um só lugar
          </h1>
          
          <p className="text-lg md:text-xl text-muted-foreground mb-10 max-w-2xl mx-auto leading-relaxed">
            Explore shows, exposições, peças de teatro, festivais e muito mais. 
            Encontre os melhores eventos culturais da cidade.
          </p>
          
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.4 }}
            className="flex flex-col sm:flex-row items-center justify-center gap-4"
          >
            <Button 
              size="lg" 
              className="bg-primary hover:bg-primary/90 text-primary-foreground font-semibold px-8"
            >
              <Calendar className="mr-2 h-5 w-5" />
              Ver Calendário
            </Button>
            <Button 
              variant="outline" 
              size="lg"
              className="border-border hover:border-primary hover:bg-primary/5"
            >
              Explorar Eventos
            </Button>
          </motion.div>
        </motion.div>
      </div>
    </section>
  );
};
