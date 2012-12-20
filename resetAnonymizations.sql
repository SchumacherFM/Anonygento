update customer_entity set anonymized = 0;
update customer_address_entity set anonymized = 0;

update newsletter_subscriber set anonymized = 0;
update gift_message set anonymized = 0;

update sales_flat_order set anonymized = 0;
update sales_flat_order_address set anonymized = 0;
update sales_flat_order_grid set anonymized = 0;
update sales_flat_order_payment set anonymized = 0;

update sales_flat_quote set anonymized = 0;
update sales_flat_quote_address set anonymized = 0;
update sales_flat_quote_payment set anonymized = 0;

update sales_flat_creditmemo_grid set anonymized = 0;
update sales_flat_invoice_grid set anonymized = 0;
update sales_flat_shipment_grid set anonymized = 0;
