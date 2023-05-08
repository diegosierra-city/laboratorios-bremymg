export interface Pedido {
 id: number,
 comprador_id: number,
 productos: Array<PedidoProduct>,
 fecha: string,
 valor: number,
 estado: string,
 pago_estado: string,
 pago_id: string,
 notas: string,
 origen: string
}

export interface Comprador {
 id: number,
 nombres: string,
 apellidos: string,
 documento: string,
 email: string,
 celular: string,
 pais: string,
 ciudad: string,
 direccion: string,
}

export interface PedidoProduct {
 id: number,
 category_id: number,
 product: string,
 ref: string,
 description: string,
 description2: string,
 size: string,
 color: string,
 image1: string,
 price: number,
 quantity: number,
 total: number, 
}