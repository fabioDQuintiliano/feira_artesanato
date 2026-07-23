import { cn } from '@/lib/utils';

interface DecorativeProps {
  className?: string;
}

// Hand-drawn wavy line
export const WavyLine = ({ className }: DecorativeProps) => (
  <svg 
    viewBox="0 0 200 20" 
    className={cn("w-full h-5", className)}
    preserveAspectRatio="none"
  >
    <path 
      d="M0,10 Q25,0 50,10 T100,10 T150,10 T200,10" 
      fill="none" 
      stroke="currentColor" 
      strokeWidth="2"
      strokeLinecap="round"
    />
  </svg>
);

// Sun rays decoration
export const SunRays = ({ className }: DecorativeProps) => (
  <svg 
    viewBox="0 0 100 100" 
    className={cn("w-24 h-24", className)}
  >
    <circle cx="50" cy="50" r="20" fill="currentColor" />
    {[...Array(12)].map((_, i) => (
      <line
        key={i}
        x1="50"
        y1="50"
        x2={50 + 35 * Math.cos((i * 30 * Math.PI) / 180)}
        y2={50 + 35 * Math.sin((i * 30 * Math.PI) / 180)}
        stroke="currentColor"
        strokeWidth="3"
        strokeLinecap="round"
        style={{ transformOrigin: 'center' }}
      />
    ))}
  </svg>
);

// Star burst decoration
export const StarBurst = ({ className }: DecorativeProps) => (
  <svg 
    viewBox="0 0 50 50" 
    className={cn("w-8 h-8", className)}
  >
    <polygon 
      points="25,2 31,18 48,18 35,29 40,46 25,36 10,46 15,29 2,18 19,18" 
      fill="currentColor"
    />
  </svg>
);

// Organic blob shape
export const BlobShape = ({ className }: DecorativeProps) => (
  <svg 
    viewBox="0 0 200 200" 
    className={cn("w-full h-full", className)}
  >
    <path 
      d="M47.5,-57.2C59.9,-47.4,67.5,-31.6,70.4,-14.8C73.3,2,71.5,19.8,63.4,33.4C55.3,47,40.9,56.4,25.2,62.1C9.5,67.8,-7.5,69.8,-23.7,66C-39.9,62.2,-55.3,52.6,-64.4,38.7C-73.5,24.8,-76.3,6.6,-73.1,-10.2C-69.9,-27,-60.7,-42.4,-47.6,-52C-34.5,-61.6,-17.3,-65.4,0.3,-65.8C17.9,-66.2,35.1,-67,47.5,-57.2Z" 
      transform="translate(100 100)"
      fill="currentColor"
    />
  </svg>
);

// Hand-drawn circle
export const HandCircle = ({ className }: DecorativeProps) => (
  <svg 
    viewBox="0 0 100 100" 
    className={cn("w-full h-full", className)}
  >
    <ellipse 
      cx="50" 
      cy="50" 
      rx="45" 
      ry="42" 
      fill="none" 
      stroke="currentColor" 
      strokeWidth="3"
      strokeLinecap="round"
      style={{ 
        strokeDasharray: '10 5',
      }}
    />
  </svg>
);

// Decorative dots pattern
export const DotsPattern = ({ className }: DecorativeProps) => (
  <svg 
    viewBox="0 0 100 100" 
    className={cn("w-full h-full", className)}
  >
    {[...Array(5)].map((_, row) =>
      [...Array(5)].map((_, col) => (
        <circle
          key={`${row}-${col}`}
          cx={10 + col * 20}
          cy={10 + row * 20}
          r="3"
          fill="currentColor"
          opacity={0.3 + Math.random() * 0.4}
        />
      ))
    )}
  </svg>
);

// Confetti pieces
export const Confetti = ({ className }: DecorativeProps) => (
  <svg 
    viewBox="0 0 100 100" 
    className={cn("w-full h-full", className)}
  >
    <rect x="10" y="20" width="8" height="12" fill="hsl(var(--tag-music))" transform="rotate(15 14 26)" />
    <rect x="30" y="40" width="6" height="10" fill="hsl(var(--tag-art))" transform="rotate(-20 33 45)" />
    <rect x="60" y="15" width="10" height="8" fill="hsl(var(--tag-dance))" transform="rotate(45 65 19)" />
    <rect x="80" y="50" width="7" height="11" fill="hsl(var(--tag-theater))" transform="rotate(-10 83.5 55.5)" />
    <rect x="45" y="70" width="9" height="7" fill="hsl(var(--tag-festival))" transform="rotate(30 49.5 73.5)" />
    <circle cx="20" cy="60" r="4" fill="hsl(var(--tag-cinema))" />
    <circle cx="75" cy="30" r="3" fill="hsl(var(--tag-workshop))" />
    <circle cx="50" cy="10" r="5" fill="hsl(var(--tag-literature))" />
  </svg>
);

// Hand-drawn arrow
export const HandArrow = ({ className }: DecorativeProps) => (
  <svg 
    viewBox="0 0 100 50" 
    className={cn("w-16 h-8", className)}
  >
    <path 
      d="M5,25 Q30,20 50,25 T95,25" 
      fill="none" 
      stroke="currentColor" 
      strokeWidth="3"
      strokeLinecap="round"
    />
    <path 
      d="M80,15 L95,25 L80,35" 
      fill="none" 
      stroke="currentColor" 
      strokeWidth="3"
      strokeLinecap="round"
      strokeLinejoin="round"
    />
  </svg>
);

// Decorative frame corners
export const FrameCorner = ({ className, position = 'top-left' }: DecorativeProps & { position?: 'top-left' | 'top-right' | 'bottom-left' | 'bottom-right' }) => {
  const rotations = {
    'top-left': 'rotate-0',
    'top-right': 'rotate-90',
    'bottom-right': 'rotate-180',
    'bottom-left': '-rotate-90',
  };
  
  return (
    <svg 
      viewBox="0 0 50 50" 
      className={cn("w-8 h-8", rotations[position], className)}
    >
      <path 
        d="M5,45 L5,5 L45,5" 
        fill="none" 
        stroke="currentColor" 
        strokeWidth="3"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
};
