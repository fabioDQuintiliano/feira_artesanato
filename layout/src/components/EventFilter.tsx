import { EventType } from '@/types/event';
import { EventTag } from './EventTag';
import { motion } from 'framer-motion';

interface EventFilterProps {
  selectedTypes: EventType[];
  onToggleType: (type: EventType) => void;
  onClearFilters: () => void;
}

const allTypes: EventType[] = [
  'music',
  'theater', 
  'cinema',
  'art',
  'dance',
  'literature',
  'festival',
  'workshop',
];

export const EventFilter = ({ selectedTypes, onToggleType, onClearFilters }: EventFilterProps) => {
  return (
    <motion.div
      initial={{ opacity: 0, y: 10 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.4 }}
      className="flex flex-wrap items-center gap-3"
    >
      <span className="text-sm text-muted-foreground font-medium mr-2">
        Filtrar por:
      </span>
      
      {allTypes.map((type, index) => (
        <motion.div
          key={type}
          initial={{ opacity: 0, scale: 0.8 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ duration: 0.3, delay: index * 0.05 }}
        >
          <EventTag
            type={type}
            size="md"
            active={selectedTypes.length === 0 || selectedTypes.includes(type)}
            onClick={() => onToggleType(type)}
          />
        </motion.div>
      ))}
      
      {selectedTypes.length > 0 && (
        <motion.button
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          onClick={onClearFilters}
          className="text-sm text-muted-foreground hover:text-primary transition-colors font-medium underline underline-offset-4"
        >
          Limpar filtros
        </motion.button>
      )}
    </motion.div>
  );
};
