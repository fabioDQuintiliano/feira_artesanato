import { motion } from 'framer-motion';
import { CulturalEvent, eventTypeLabels, ageRestrictionLabels, ageRestrictionShort } from '@/types/event';
import { EventTag } from './EventTag';
import { 
  Calendar, 
  Clock, 
  MapPin, 
  DollarSign, 
  Users, 
  Timer, 
  Building2, 
  X,
  ExternalLink,
  Share2
} from 'lucide-react';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { cn } from '@/lib/utils';

interface EventDetailModalProps {
  event: CulturalEvent | null;
  open: boolean;
  onOpenChange: (open: boolean) => void;
}

export const EventDetailModal = ({ event, open, onOpenChange }: EventDetailModalProps) => {
  if (!event) return null;

  const formatPrice = (price?: number) => {
    if (price === undefined) return 'Consulte';
    if (price === 0) return 'Gratuito';
    return `R$ ${price.toFixed(2).replace('.', ',')}`;
  };

  const getAgeRestrictionColor = (restriction?: string) => {
    if (!restriction) return 'bg-green-500/20 text-green-400 border-green-500/30';
    switch (restriction) {
      case 'livre': return 'bg-green-500/20 text-green-400 border-green-500/30';
      case '10': return 'bg-blue-500/20 text-blue-400 border-blue-500/30';
      case '12': return 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30';
      case '14': return 'bg-orange-500/20 text-orange-400 border-orange-500/30';
      case '16': return 'bg-red-500/20 text-red-400 border-red-500/30';
      case '18': return 'bg-red-600/20 text-red-500 border-red-600/30';
      case 'menores_acompanhados': return 'bg-purple-500/20 text-purple-400 border-purple-500/30';
      default: return 'bg-green-500/20 text-green-400 border-green-500/30';
    }
  };

  const handleShare = async () => {
    if (navigator.share) {
      try {
        await navigator.share({
          title: event.title,
          text: event.description,
          url: window.location.href,
        });
      } catch (err) {
        console.log('Share cancelled');
      }
    }
  };

  const openInMaps = () => {
    if (event.coordinates) {
      window.open(
        `https://www.google.com/maps?q=${event.coordinates.lat},${event.coordinates.lng}`,
        '_blank'
      );
    } else if (event.address) {
      window.open(
        `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(event.address)}`,
        '_blank'
      );
    }
  };

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto bg-card border-border p-0">
        {/* Hero Image */}
        {event.image && (
          <div className="relative h-64 w-full overflow-hidden">
            <img
              src={event.image}
              alt={event.title}
              className="w-full h-full object-cover"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-card via-card/50 to-transparent" />
            
            {/* Tags overlay */}
            <div className="absolute top-4 left-4 flex items-center gap-2">
              <EventTag type={event.type} size="sm" />
              {event.featured && (
                <span className="inline-flex items-center gap-1 text-xs font-semibold text-primary bg-primary/20 backdrop-blur-sm px-2 py-1 rounded-full border border-primary/30">
                  ★ Destaque
                </span>
              )}
            </div>
          </div>
        )}
        
        <div className="p-6">
          <DialogHeader className="mb-4">
            <DialogTitle className="font-heading text-2xl md:text-3xl font-bold text-foreground text-left">
              {event.title}
            </DialogTitle>
          </DialogHeader>

          {/* Quick Info Cards */}
          <div className="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
            {/* Price */}
            <div className="bg-secondary/50 rounded-lg p-3 border border-border">
              <div className="flex items-center gap-2 text-muted-foreground mb-1">
                <DollarSign className="h-4 w-4" />
                <span className="text-xs">Valor</span>
              </div>
              <p className={cn(
                "font-semibold",
                event.price === 0 ? "text-green-400" : "text-foreground"
              )}>
                {formatPrice(event.price)}
              </p>
            </div>

            {/* Age Restriction */}
            <div className="bg-secondary/50 rounded-lg p-3 border border-border">
              <div className="flex items-center gap-2 text-muted-foreground mb-1">
                <Users className="h-4 w-4" />
                <span className="text-xs">Classificação</span>
              </div>
              <span className={cn(
                "inline-flex px-2 py-0.5 rounded text-xs font-semibold border",
                getAgeRestrictionColor(event.ageRestriction)
              )}>
                {event.ageRestriction ? ageRestrictionShort[event.ageRestriction] : 'Livre'}
              </span>
            </div>

            {/* Date */}
            <div className="bg-secondary/50 rounded-lg p-3 border border-border">
              <div className="flex items-center gap-2 text-muted-foreground mb-1">
                <Calendar className="h-4 w-4" />
                <span className="text-xs">Data</span>
              </div>
              <p className="font-semibold text-foreground text-sm">
                {format(event.date, "dd/MM/yyyy", { locale: ptBR })}
              </p>
            </div>

            {/* Time */}
            <div className="bg-secondary/50 rounded-lg p-3 border border-border">
              <div className="flex items-center gap-2 text-muted-foreground mb-1">
                <Clock className="h-4 w-4" />
                <span className="text-xs">Horário</span>
              </div>
              <p className="font-semibold text-foreground">{event.time}</p>
            </div>
          </div>

          {/* Age Restriction Full Text */}
          {event.ageRestriction && (
            <div className={cn(
              "rounded-lg p-3 mb-6 border",
              getAgeRestrictionColor(event.ageRestriction)
            )}>
              <div className="flex items-start gap-2">
                <Users className="h-4 w-4 mt-0.5 flex-shrink-0" />
                <p className="text-sm">
                  {ageRestrictionLabels[event.ageRestriction]}
                </p>
              </div>
            </div>
          )}

          {/* Description */}
          <div className="mb-6">
            <h3 className="font-semibold text-foreground mb-2">Sobre o evento</h3>
            <p className="text-muted-foreground leading-relaxed">
              {event.description}
            </p>
          </div>

          <Separator className="my-6 bg-border" />

          {/* Additional Info */}
          <div className="space-y-4 mb-6">
            {event.priceInfo && (
              <div className="flex items-start gap-3">
                <DollarSign className="h-5 w-5 text-primary flex-shrink-0 mt-0.5" />
                <div>
                  <span className="text-sm text-muted-foreground">Informação de preço:</span>
                  <p className="text-foreground">{event.priceInfo}</p>
                </div>
              </div>
            )}

            {event.duration && (
              <div className="flex items-start gap-3">
                <Timer className="h-5 w-5 text-accent flex-shrink-0 mt-0.5" />
                <div>
                  <span className="text-sm text-muted-foreground">Duração:</span>
                  <p className="text-foreground">{event.duration}</p>
                </div>
              </div>
            )}

            {event.organizer && (
              <div className="flex items-start gap-3">
                <Building2 className="h-5 w-5 text-muted-foreground flex-shrink-0 mt-0.5" />
                <div>
                  <span className="text-sm text-muted-foreground">Organização:</span>
                  <p className="text-foreground">{event.organizer}</p>
                </div>
              </div>
            )}
          </div>

          <Separator className="my-6 bg-border" />

          {/* Location */}
          <div className="mb-6">
            <h3 className="font-semibold text-foreground mb-3 flex items-center gap-2">
              <MapPin className="h-5 w-5 text-primary" />
              Localização
            </h3>
            <div className="space-y-2 mb-4">
              <p className="text-foreground font-medium">{event.location}</p>
              {event.address && (
                <p className="text-muted-foreground text-sm">{event.address}</p>
              )}
            </div>

            {/* Map Embed */}
            {(event.coordinates || event.address) && (
              <div className="relative rounded-lg overflow-hidden border border-border bg-secondary/30">
                <iframe
                  width="100%"
                  height="200"
                  style={{ border: 0 }}
                  loading="lazy"
                  allowFullScreen
                  referrerPolicy="no-referrer-when-downgrade"
                  src={
                    event.coordinates
                      ? `https://www.google.com/maps/embed/v1/place?key=${import.meta.env.VITE_GOOGLE_MAPS_API_KEY || ""}&q=${event.coordinates.lat},${event.coordinates.lng}&zoom=15`
                      : `https://www.google.com/maps/embed/v1/place?key=${import.meta.env.VITE_GOOGLE_MAPS_API_KEY || ""}&q=${encodeURIComponent(event.address || event.location)}`
                  }
                />
                <Button
                  variant="secondary"
                  size="sm"
                  className="absolute bottom-3 right-3 gap-2"
                  onClick={openInMaps}
                >
                  <ExternalLink className="h-4 w-4" />
                  Abrir no Maps
                </Button>
              </div>
            )}
          </div>

          {/* Action Buttons */}
          <div className="flex flex-col sm:flex-row gap-3">
            <Button className="flex-1 gap-2" size="lg">
              Comprar Ingresso
            </Button>
            <Button 
              variant="outline" 
              size="lg" 
              className="gap-2"
              onClick={handleShare}
            >
              <Share2 className="h-4 w-4" />
              Compartilhar
            </Button>
          </div>
        </div>
      </DialogContent>
    </Dialog>
  );
};
