export interface MenuWeb {
    id: number;
    menu: string;
    link: string;
    submenu: boolean;
    submenus: Array<MenuWeb>;

}