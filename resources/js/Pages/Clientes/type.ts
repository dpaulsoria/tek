import { Cite } from "../Citas/type";

export interface Person {
    phone: string;
    address: string;
    id: number;
    document: string;
    first_name: string;
    last_name: string;
    email: string;
    cites: Cite[];
}
