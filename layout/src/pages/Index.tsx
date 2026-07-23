import { useState, useMemo } from 'react';
import { motion } from 'framer-motion';
import { Header } from '@/components/Header';
import { Hero } from '@/components/Hero';
import { SponsorBanner } from '@/components/SponsorBanner';
import { FeaturedEvents } from '@/components/FeaturedEvents';
import { EventCalendar } from '@/components/EventCalendar';
import { AIRecommendationSection } from '@/components/AIRecommendationSection';
import { EventFilter } from '@/components/EventFilter';
import { EventCard } from '@/components/EventCard';
import { Footer } from '@/components/Footer';
import { mockEvents } from '@/data/events';
import { EventType } from '@/types/event';
import { Calendar, LayoutGrid } from 'lucide-react';

const Index = () => {
  const [selectedTypes, setSelectedTypes] = useState<EventType[]>([]);
  const [selectedDate, setSelectedDate] = useState<Date | undefined>(undefined);
  
  const handleToggleType = (type: EventType) => {
    setSelectedTypes(prev => 
      prev.includes(type) 
        ? prev.filter(t => t !== type)
        : [...prev, type]
    );
  };
  
  const handleClearFilters = () => {
    setSelectedTypes([]);
  };
  
  const filteredEvents = useMemo(() => {
    return mockEvents.filter(event => {
      if (selectedTypes.length > 0 && !selectedTypes.includes(event.type)) {
        return false;
      }
      return true;
    });
  }, [selectedTypes]);

  // Get a sponsored event (first featured event as example)
  const sponsoredEvent = mockEvents.find(e => e.featured);
  
  return (
    <div className="min-h-screen bg-background">
      <Header />
      <Hero />
      
      {/* Sponsor Banner - Discreto abaixo do Hero */}
      <SponsorBanner 
        sponsorName="Marca Exemplo"
        message="Apoiando a cultura brasileira"
        sponsorUrl="https://example.com"
      />
      
      <FeaturedEvents events={mockEvents} sponsoredEvent={sponsoredEvent} />
      
      {/* AI Recommendation Section */}
      <AIRecommendationSection />
      
      {/* Calendar Section */}
      <section id="calendario" className="py-16 md:py-24 bg-secondary/30">
        <div className="container px-4">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="flex items-center gap-3 mb-8"
          >
            <div className="p-2 bg-primary/10 rounded-lg">
              <Calendar className="h-6 w-6 text-primary" />
            </div>
            <h2 className="font-heading text-2xl md:text-3xl font-semibold text-foreground">
              Calendário de <span className="text-primary">Eventos</span>
            </h2>
          </motion.div>
          
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
          >
            <EventCalendar 
              events={filteredEvents} 
              onDateSelect={setSelectedDate}
            />
          </motion.div>
        </div>
      </section>
      
      {/* All Events Section */}
      <section id="eventos" className="py-16 md:py-24">
        <div className="container px-4">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5 }}
            className="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8"
          >
            <div className="flex items-center gap-3">
              <div className="p-2 bg-accent/10 rounded-lg">
                <LayoutGrid className="h-6 w-6 text-accent" />
              </div>
              <h2 className="font-heading text-2xl md:text-3xl font-semibold text-foreground">
                Todos os <span className="text-accent">Eventos</span>
              </h2>
              <span className="text-sm text-muted-foreground bg-secondary px-3 py-1 rounded-full font-medium">
                {filteredEvents.length}
              </span>
            </div>
          </motion.div>
          
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: 0.1 }}
            className="mb-8"
            id="categorias"
          >
            <EventFilter 
              selectedTypes={selectedTypes}
              onToggleType={handleToggleType}
              onClearFilters={handleClearFilters}
            />
          </motion.div>
          
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {filteredEvents.map((event, index) => (
              <EventCard key={event.id} event={event} index={index} />
            ))}
          </div>
          
          {filteredEvents.length === 0 && (
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              className="text-center py-16 bg-card rounded-xl border border-dashed border-border"
            >
              <p className="text-muted-foreground text-lg">
                Nenhum evento encontrado com os filtros selecionados.
              </p>
            </motion.div>
          )}
        </div>
      </section>
      
      <Footer />
    </div>
  );
};

export default Index;
