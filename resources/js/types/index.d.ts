// ============================================================================
// AutoScan - TypeScript Declarations
// ============================================================================

// ---- Pagination ----
export interface PaginationData<T> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: { url: string | null; label: string; active: boolean }[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

// ---- Flash Message ----
export interface FlashMessage {
    success?: string;
    error?: string;
    warning?: string;
    info?: string;
}

// ---- Notification ----
export interface AppNotification {
    id: string;
    type: string;
    data: {
        title?: string;
        message?: string;
        body?: string;
        order_number?: string;
        quotation_number?: string;
        [key: string]: unknown;
    };
    read_at: string | null;
    created_at: string;
}

// ---- User ----
export interface User {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    role: 'admin' | 'technician' | 'client';
    avatar: string | null;
    is_active: boolean;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    role_label?: string;
    initials?: string;
}

// ---- Vehicle ----
export interface Vehicle {
    id: number;
    client_id: number;
    brand: string;
    model: string;
    year: number;
    plate: string;
    color: string | null;
    vin: string | null;
    mileage: number | null;
    engine_type: string | null;
    transmission: string | null;
    notes: string | null;
    status: 'active' | 'in_service' | 'sold' | 'inactive';
    full_name?: string;
    plate_formatted?: string;
    status_label?: string;
    engine_type_label?: string;
    transmission_label?: string;
    mileage_formatted?: string;
    client?: User;
    service_orders?: ServiceOrder[];
    created_at: string;
    updated_at: string;
}

// ---- Service Order ----
export interface ServiceOrder {
    id: number;
    order_number: string;
    vehicle_id: number;
    client_id: number;
    technician_id: number | null;
    service_type: string;
    description: string | null;
    diagnosis: string | null;
    status: string;
    priority: string;
    estimated_cost: number | null;
    actual_cost: number | null;
    estimated_completion_date: string | null;
    started_at: string | null;
    completed_at: string | null;
    delivered_at: string | null;
    notes: string | null;
    status_label?: string;
    priority_label?: string;
    service_type_label?: string;
    formatted_estimated_cost?: string;
    formatted_actual_cost?: string;
    is_overdue?: boolean;
    duration_days?: number | null;
    vehicle?: Vehicle;
    client?: User;
    technician?: User;
    reports?: ServiceReport[];
    quotation?: Quotation;
    created_at: string;
    updated_at: string;
}

// ---- Service Report ----
export interface ServiceReport {
    id: number;
    service_order_id: number;
    technician_id: number;
    report_date: string;
    description: string | null;
    work_performed: string | null;
    labor_hours: number | null;
    parts_used: PartUsed[] | null;
    findings: string | null;
    recommendations: string | null;
    images: string[] | null;
    notes: string | null;
    formatted_labor_hours?: string;
    parts_count?: number;
    images_count?: number;
    parts_summary?: string;
    service_order?: ServiceOrder;
    technician?: User;
    created_at: string;
    updated_at: string;
}

export interface PartUsed {
    name: string;
    quantity: number;
    part_number?: string;
}

// ---- Product ----
export interface Product {
    id: number;
    name: string;
    slug: string;
    sku: string | null;
    description: string | null;
    category: string;
    price: number;
    cost: number | null;
    stock: number;
    min_stock: number;
    unit: string | null;
    is_active: boolean;
    barcode: string | null;
    formatted_price?: string;
    formatted_cost?: string;
    profit_margin?: number;
    profit_amount?: number;
    category_label?: string;
    stock_status?: string;
    stock_status_color?: string;
    created_at: string;
    updated_at: string;
}

// ---- Quotation Item ----
export interface QuotationItem {
    id: number;
    quotation_id: number;
    product_id: number | null;
    description: string;
    quantity: number;
    unit_price: number;
    discount: number | null;
    discount_type: string | null;
    total: number;
    notes: string | null;
    formatted_unit_price?: string;
    formatted_discount?: string;
    formatted_total?: string;
    effective_price?: number;
    product?: Product;
}

// ---- Quotation ----
export interface Quotation {
    id: number;
    quotation_number: string;
    client_id: number;
    vehicle_id: number | null;
    technician_id: number | null;
    service_order_id: number | null;
    description: string | null;
    status: string;
    subtotal: number;
    tax_rate: number | null;
    tax: number;
    discount: number | null;
    discount_type: string | null;
    total: number;
    valid_until: string | null;
    approved_at: string | null;
    rejected_at: string | null;
    notes: string | null;
    terms_and_conditions: string | null;
    status_label?: string;
    formatted_subtotal?: string;
    formatted_tax?: string;
    formatted_discount?: string;
    formatted_total?: string;
    is_expired?: boolean;
    item_count?: number;
    client?: User;
    vehicle?: Vehicle;
    technician?: User;
    service_order?: ServiceOrder;
    items?: QuotationItem[];
    created_at: string;
    updated_at: string;
}

// ---- Sale Item ----
export interface SaleItem {
    id: number;
    sale_id: number;
    product_id: number | null;
    description: string;
    quantity: number;
    unit_price: number;
    cost: number | null;
    discount: number | null;
    discount_type: string | null;
    total: number;
    notes: string | null;
    formatted_unit_price?: string;
    formatted_cost?: string;
    formatted_discount?: string;
    formatted_total?: string;
    effective_price?: number;
    profit?: number;
    profit_margin?: number;
    product?: Product;
}

// ---- Sale ----
export interface Sale {
    id: number;
    sale_number: string;
    client_id: number;
    quotation_id: number | null;
    description: string | null;
    status: string;
    subtotal: number;
    tax_rate: number | null;
    tax: number;
    discount: number | null;
    discount_type: string | null;
    total: number;
    paid_amount: number | null;
    payment_method: string | null;
    paid_at: string | null;
    notes: string | null;
    status_label?: string;
    payment_method_label?: string;
    formatted_subtotal?: string;
    formatted_tax?: string;
    formatted_discount?: string;
    formatted_total?: string;
    formatted_paid_amount?: string;
    remaining_amount?: number;
    formatted_remaining_amount?: string;
    is_fully_paid?: boolean;
    change_amount?: number;
    item_count?: number;
    client?: User;
    quotation?: Quotation;
    items?: SaleItem[];
    created_at: string;
    updated_at: string;
}

// ---- Select Option ----
export interface SelectOption {
    value: string | number;
    label: string;
}

// ---- Dashboard Stats ----
export interface DashboardStats {
    total_orders: number;
    active_orders: number;
    completed_this_month: number;
    monthly_revenue: number;
    assigned_orders?: number;
    pending_diagnostics?: number;
    completed_today?: number;
}

// ---- Shared Props ----
export interface SharedProps {
    auth: {
        user: User | null;
    };
    flash: FlashMessage;
    ziggy: {
        location: string;
        [key: string]: unknown;
    };
    notifications?: AppNotification[];
    unread_count?: number;
}

// ---- Page Props ----
export interface PageProps extends SharedProps {
    [key: string]: unknown;
}
