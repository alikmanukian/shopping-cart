export interface Product {
    id: number;
    name: string;
    slug: string;
    description: string;
    price: string;
    stock_quantity: number;
    image: string | null;
    image_url: string | null;
    is_active: boolean;
    formatted_price: string;
    is_low_stock: boolean;
    is_in_stock: boolean;
    created_at: string;
    updated_at: string;
}

export interface CartItem {
    id: number;
    cart_id: number;
    product_id: number;
    quantity: number;
    subtotal: string;
    formatted_subtotal: string;
    product: Product;
    created_at: string;
    updated_at: string;
}

export interface Cart {
    id: number;
    user_id: number;
    items: CartItem[];
    items_count: number;
    subtotal: string;
    formatted_subtotal: string;
    created_at: string;
    updated_at: string;
}

export interface OrderItem {
    id: number;
    order_id: number;
    product_id: number;
    product_name: string;
    product_price: string;
    unit_price: string;
    quantity: number;
    subtotal: string;
    formatted_subtotal: string;
    product?: Product;
    created_at: string;
    updated_at: string;
}

export interface Order {
    id: number;
    user_id: number;
    order_number: string;
    status: 'pending' | 'processing' | 'completed' | 'cancelled';
    subtotal: string;
    total: string;
    formatted_subtotal: string;
    formatted_total: string;
    items: OrderItem[];
    completed_at: string | null;
    created_at: string;
    updated_at: string;
}

export type OrderStatus = Order['status'];
