// resources/js/Types.ts
export interface Paginated<T> {
  data: T[];
  current_page: number;
  per_page: number;
  total: number;
  last_page: number;
  from: number;
  to: number;
}

export interface User {
    id: number;
    name: string;
    email: string;
}

export interface SelectOption {
    value: number;
    label: string;
}
