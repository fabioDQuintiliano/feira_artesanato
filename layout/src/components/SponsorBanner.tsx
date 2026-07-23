import { motion } from 'framer-motion';
import { ExternalLink } from 'lucide-react';

interface SponsorBannerProps {
  sponsorName?: string;
  sponsorLogo?: string;
  sponsorUrl?: string;
  message?: string;
}

export const SponsorBanner = ({ 
  sponsorName = "Seu Patrocinador", 
  sponsorLogo,
  sponsorUrl = "#",
  message = "Apoiando a cultura local"
}: SponsorBannerProps) => {
  return (
    <motion.div
      initial={{ opacity: 0, y: 10 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5, delay: 0.3 }}
      className="w-full bg-secondary/50 border-y border-border/50"
    >
      <div className="container px-4">
        <a 
          href={sponsorUrl}
          target="_blank"
          rel="noopener noreferrer"
          className="flex items-center justify-center gap-4 py-3 group"
        >
          <span className="text-xs text-muted-foreground uppercase tracking-wider">
            Patrocinado por
          </span>
          
          {sponsorLogo ? (
            <img 
              src={sponsorLogo} 
              alt={sponsorName}
              className="h-6 md:h-8 object-contain opacity-70 group-hover:opacity-100 transition-all"
            />
          ) : (
            <span className="font-heading font-semibold text-foreground group-hover:text-primary transition-colors">
              {sponsorName}
            </span>
          )}
          
          <span className="hidden md:inline-flex items-center gap-1 text-sm text-muted-foreground group-hover:text-primary transition-colors">
            {message}
            <ExternalLink className="h-3 w-3" />
          </span>
        </a>
      </div>
    </motion.div>
  );
};
