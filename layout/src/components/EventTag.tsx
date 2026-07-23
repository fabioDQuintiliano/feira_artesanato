import { EventType, eventTypeLabels } from '@/types/event';
import { cn } from '@/lib/utils';

interface EventTagProps {
  type: EventType;
  size?: 'sm' | 'md' | 'lg';
  onClick?: () => void;
  active?: boolean;
}

const tagColorClasses: Record<EventType, string> = {
  music: 'bg-tag-music',
  theater: 'bg-tag-theater',
  cinema: 'bg-tag-cinema',
  art: 'bg-tag-art',
  dance: 'bg-tag-dance',
  literature: 'bg-tag-literature',
  festival: 'bg-tag-festival',
  workshop: 'bg-tag-workshop',
};

export const EventTag = ({ type, size = 'md', onClick, active = true }: EventTagProps) => {
  const sizeClasses = {
    sm: 'px-2 py-1 text-xs',
    md: 'px-3 py-1.5 text-sm',
    lg: 'px-4 py-2 text-base',
  };

  return (
    <span
      onClick={onClick}
      className={cn(
        'inline-flex items-center rounded-full font-medium transition-all duration-200 text-white',
        sizeClasses[size],
        tagColorClasses[type],
        active ? 'opacity-100' : 'opacity-40',
        onClick && 'cursor-pointer hover:scale-105 hover:shadow-lg'
      )}
    >
      {eventTypeLabels[type]}
    </span>
  );
};
