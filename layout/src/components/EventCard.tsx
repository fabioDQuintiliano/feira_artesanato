import { useState } from 'react';
import { motion } from 'framer-motion';
import { CulturalEvent, ageRestrictionShort } from '@/types/event';
import { EventTag } from './EventTag';
import { EventDetailModal } from './EventDetailModal';
import { Calendar, Clock, MapPin, DollarSign } from 'lucide-react';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import { cn } from '@/lib/utils';

interface EventCardProps {
  event: CulturalEvent;
  index?: number;
}

export const EventCard = ({ event, index = 0 }: EventCardProps) => {
  const [isModalOpen, setIsModalOpen] = useState(false);

  const formatPrice = (price?: number) => {
    if (price === undefined) return null;
    if (price === 0) return 'Gratuito';
    return `R$ ${price.toFixed(0)}`;
  };

  return (
    <>
      <motion.article
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.4, delay: index * 0.1 }}
        whileHover={{ y: -4 }}
        onClick={() => setIsModalOpen(true)}
        className={cn(
          "group relative overflow-hidden rounded-xl bg-card border border-border transition-all duration-300 cursor-pointer",
          "hover:border-primary/50 hover:shadow-lg hover:shadow-primary/5"
        )}
      >
        {/* Event Image */}
        {event.image && (
          <div className="relative h-48 overflow-hidden">
            <img
              src={event.image}
              alt={event.title}
              className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-card via-transparent to-transparent" />
            
            {/* Tag overlay */}
            <div className="absolute top-3 left-3">
              <EventTag type={event.type} size="sm" />
            </div>
            
            {/* Featured badge */}
            {event.featured && (
              <div className="absolute top-3 right-3">
                <span className="inline-flex items-center gap-1 text-xs font-semibold text-primary bg-primary/20 backdrop-blur-sm px-2 py-1 rounded-full border border-primary/30">
                  ★ Destaque
                </span>
              </div>
            )}

            {/* Price badge */}
            {formatPrice(event.price) && (
              <div className="absolute bottom-3 right-3">
                <span className={cn(
                  "inline-flex items-center gap-1 text-xs font-semibold backdrop-blur-sm px-2 py-1 rounded-full border",
                  event.price === 0 
                    ? "text-green-400 bg-green-500/20 border-green-500/30" 
                    : "text-foreground bg-background/80 border-border"
                )}>
                  {formatPrice(event.price)}
                </span>
              </div>
            )}
          </div>
        )}
        
        <div className="p-5">
          {/* Show tag here if no image */}
          {!event.image && (
            <div className="flex items-start justify-between gap-3 mb-3">
              <EventTag type={event.type} size="sm" />
              {event.featured && (
                <span className="text-xs font-semibold text-primary">★ Destaque</span>
              )}
            </div>
          )}
          
          <h3 className="font-heading text-lg font-semibold text-foreground mb-2 group-hover:text-primary transition-colors line-clamp-2">
            {event.title}
          </h3>
          
          <p className="text-sm text-muted-foreground mb-4 line-clamp-2">
            {event.description}
          </p>
          
          <div className="space-y-2 text-sm text-muted-foreground">
            <div className="flex items-center gap-2">
              <Calendar className="h-4 w-4 text-primary" />
              <span>{format(event.date, "d 'de' MMMM", { locale: ptBR })}</span>
            </div>
            <div className="flex items-center gap-2">
              <Clock className="h-4 w-4 text-accent" />
              <span>{event.time}</span>
            </div>
            <div className="flex items-center gap-2">
              <MapPin className="h-4 w-4 text-muted-foreground" />
              <span>{event.location}</span>
            </div>
          </div>

          {/* Age restriction indicator */}
          {event.ageRestriction && event.ageRestriction !== 'livre' && (
            <div className="mt-3 pt-3 border-t border-border/50">
              <span className="inline-flex items-center gap-1 text-xs font-medium text-muted-foreground">
                Classificação: {ageRestrictionShort[event.ageRestriction]}
              </span>
            </div>
          )}
        </div>
      </motion.article>

      <EventDetailModal 
        event={event} 
        open={isModalOpen} 
        onOpenChange={setIsModalOpen} 
      />
    </>
  );
};
