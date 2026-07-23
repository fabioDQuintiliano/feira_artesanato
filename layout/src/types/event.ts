export type EventType = 
  | 'music' 
  | 'theater' 
  | 'cinema' 
  | 'art' 
  | 'dance' 
  | 'literature' 
  | 'festival' 
  | 'workshop';

export type AgeRestriction = 
  | 'livre'
  | '10'
  | '12'
  | '14'
  | '16'
  | '18'
  | 'menores_acompanhados';

export interface CulturalEvent {
  id: string;
  title: string;
  description: string;
  date: Date;
  time: string;
  location: string;
  address?: string;
  coordinates?: {
    lat: number;
    lng: number;
  };
  type: EventType;
  image?: string;
  featured?: boolean;
  price?: number;
  priceInfo?: string;
  ageRestriction?: AgeRestriction;
  duration?: string;
  organizer?: string;
}

export const eventTypeLabels: Record<EventType, string> = {
  music: 'Música',
  theater: 'Teatro',
  cinema: 'Cinema',
  art: 'Arte',
  dance: 'Dança',
  literature: 'Literatura',
  festival: 'Festival',
  workshop: 'Workshop',
};

export const eventTypeColors: Record<EventType, string> = {
  music: 'tag-music',
  theater: 'tag-theater',
  cinema: 'tag-cinema',
  art: 'tag-art',
  dance: 'tag-dance',
  literature: 'tag-literature',
  festival: 'tag-festival',
  workshop: 'tag-workshop',
};

export const ageRestrictionLabels: Record<AgeRestriction, string> = {
  livre: 'Livre para todos os públicos',
  '10': 'Não recomendado para menores de 10 anos',
  '12': 'Não recomendado para menores de 12 anos',
  '14': 'Não recomendado para menores de 14 anos',
  '16': 'Não recomendado para menores de 16 anos',
  '18': 'Proibido para menores de 18 anos',
  menores_acompanhados: 'Menores somente acompanhados dos pais ou responsáveis',
};

export const ageRestrictionShort: Record<AgeRestriction, string> = {
  livre: 'Livre',
  '10': '10+',
  '12': '12+',
  '14': '14+',
  '16': '16+',
  '18': '18+',
  menores_acompanhados: 'Menores acompanhados',
};
