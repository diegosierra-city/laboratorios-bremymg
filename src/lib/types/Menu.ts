export interface Menu {
    id: number;
    menu_id: number;
    menu: string;
    type: string;
    link: string;
    head: boolean;
    foot: boolean;
    side: boolean;
    position: number;
    submenu: boolean;
    submenus: Array<Menu>;
    metadescription: string;
    metakeywords: string;
}