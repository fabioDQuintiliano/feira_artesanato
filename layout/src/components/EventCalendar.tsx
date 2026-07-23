import { useState } from 'react';
import { Calendar } from '@/components/ui/calendar';
import { CulturalEvent } from '@/types/event';
import { format, isSameDay } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import { motion, AnimatePresence } from 'framer-motion';
import { EventTag } from './EventTag';
import { Clock, MapPin } from 'lucide-react';

interface EventCalendarProps {
  events: CulturalEvent[];
  onDateSelect?: (date: Date | undefined) => void;
}

export const EventCalendar = ({ events, onDateSelect }: EventCalendarProps) => {
  const [selectedDate, setSelectedDate] = useState<Date | undefined>(undefined);
  
  // Get events for a specific date
  const getEventsForDate = (date: Date) => {
    return events.filter(event => isSameDay(event.date, date));
  };
  
  // Dates with events
  const datesWithEvents = events.map(event => event.date);
  
  const handleDateSelect = (date: Date | undefined) => {
    setSelectedDate(date);
    onDateSelect?.(date);
  };
  
  const selectedDateEvents = selectedDate ? getEventsForDate(selectedDate) : [];
  
  // Custom day content to show event indicators
  const modifiers = {
    hasEvent: (date: Date) => datesWithEvents.some(d => isSameDay(d, date)),
  };
  
  const modifiersClassNames = {
    hasEvent: 'relative after:absolute after:bottom-1 after:left-1/2 after:-translate-x-1/2 after:w-1.5 after:h-1.5 after:rounded-full after:bg-primary',
  };

  return (
    <div className="flex flex-col lg:flex-row gap-6">
      <div className="bg-card rounded-xl border border-border p-4">
        <Calendar
          mode="single"
          selected={selectedDate}
          onSelect={handleDateSelect}
          locale={ptBR}
          modifiers={modifiers}
          modifiersClassNames={modifiersClassNames}
          className="pointer-events-auto"
          classNames={{
            months: "flex flex-col sm:flex-row space-y-4 sm:space-x-4 sm:space-y-0",
            month: "space-y-4",
            caption: "flex justify-center pt-1 relative items-center",
            caption_label: "text-sm font-semibold text-foreground",
            nav: "space-x-1 flex items-center",
            nav_button: "h-8 w-8 bg-secondary p-0 hover:bg-primary/10 hover:text-primary rounded-lg transition-colors border border-border",
            nav_button_previous: "absolute left-1",
            nav_button_next: "absolute right-1",
            table: "w-full border-collapse space-y-1",
            head_row: "flex",
            head_cell: "text-muted-foreground rounded-md w-9 font-medium text-[0.8rem]",
            row: "flex w-full mt-2",
            cell: "h-9 w-9 text-center text-sm p-0 relative",
            day: "h-9 w-9 p-0 font-medium rounded-lg hover:bg-primary/10 hover:text-primary transition-colors",
            day_range_end: "day-range-end",
            day_selected: "bg-primary text-primary-foreground hover:bg-primary hover:text-primary-foreground focus:bg-primary focus:text-primary-foreground rounded-lg",
            day_today: "bg-accent/20 text-accent font-semibold",
            day_outside: "text-muted-foreground opacity-50",
            day_disabled: "text-muted-foreground opacity-50",
            day_hidden: "invisible",
          }}
        />
      </div>
      
      <div className="flex-1 min-w-0">
        <AnimatePresence mode="wait">
          {selectedDate ? (
            <motion.div
              key={selectedDate.toISOString()}
              initial={{ opacity: 0, x: 20 }}
              animate={{ opacity: 1, x: 0 }}
              exit={{ opacity: 0, x: -20 }}
              transition={{ duration: 0.3 }}
              className="bg-card rounded-xl border border-border p-5"
            >
              <h3 className="font-heading text-lg font-semibold mb-4 text-foreground">
                {format(selectedDate, "d 'de' MMMM, EEEE", { locale: ptBR })}
              </h3>
              
              {selectedDateEvents.length > 0 ? (
                <div className="space-y-3">
                  {selectedDateEvents.map((event) => (
                    <div
                      key={event.id}
                      className="p-4 rounded-lg border-l-4 bg-secondary/50"
                      style={{ borderLeftColor: `hsl(var(--tag-${event.type}))` }}
                    >
                      <div className="flex items-start justify-between gap-2 mb-2">
                        <h4 className="font-heading font-semibold text-foreground">{event.title}</h4>
                        <EventTag type={event.type} size="sm" />
                      </div>
                      <div className="flex flex-wrap gap-4 text-sm text-muted-foreground">
                        <span className="flex items-center gap-1">
                          <Clock className="h-3.5 w-3.5 text-accent" />
                          {event.time}
                        </span>
                        <span className="flex items-center gap-1">
                          <MapPin className="h-3.5 w-3.5 text-muted-foreground" />
                          {event.location}
                        </span>
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <p className="text-muted-foreground">
                  Nenhum evento programado para esta data.
                </p>
              )}
            </motion.div>
          ) : (
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              className="bg-card rounded-xl border border-border border-dashed p-8 h-full flex items-center justify-center"
            >
              <p className="text-muted-foreground text-center">
                Selecione uma data no calendário para ver os eventos
              </p>
            </motion.div>
          )}
        </AnimatePresence>
      </div>
    </div>
  );
};
