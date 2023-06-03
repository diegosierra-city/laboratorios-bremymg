export interface Pedido {
 id: number,
 company_id: number,
 order_id: number,
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
 company_id: number,
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
 company_id: number,
 order_id: number,
 category_id: number,
 product_id: number,
 name: string,
 ref: string,
 image: string,
 description: string,
 description2: string,
 size: string,
 color: string,
 version_type: string,
 version: string,
 image_version: string,
 price: number,
 quantity: number,
 total: number,
}

