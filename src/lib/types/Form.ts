export interface Form {
    id: number;
    menu_id: number;
    name: string;
    type: string;
    required: boolean;
    response: any; //accept checkbox
    position: number;
}