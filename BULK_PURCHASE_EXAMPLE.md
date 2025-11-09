# Bulk Purchase & Individual Sales Example

## 🎯 Your Business Scenario Implementation

### **Scenario: Screws Purchase & Sales**

#### **Purchase Details:**
- **Product**: M8 Screws
- **Purchase Unit**: Box
- **Purchase Quantity**: 1 Box
- **Purchase Price**: ₹500 per box
- **Box Contains**: 12 Pieces
- **Conversion Factor**: 12 (1 Box = 12 Pieces)

#### **System Calculation:**
```
Cost per Piece = Purchase Price ÷ (Purchase Quantity × Conversion Factor)
Cost per Piece = ₹500 ÷ (1 × 12) = ₹41.67 per piece
Available Sale Quantity = Purchase Quantity × Conversion Factor
Available Sale Quantity = 1 × 12 = 12 pieces
```

### **Sales Scenarios:**

#### **Customer A - Retail Customer:**
- **Order**: 3 Pieces
- **Selling Price**: ₹50 per piece
- **Total Sale**: ₹150
- **Cost**: ₹41.67 × 3 = ₹125.01
- **Profit**: ₹150 - ₹125.01 = ₹24.99
- **Profit Margin**: 20%

#### **Customer B - Wholesale Customer:**
- **Order**: 6 Pieces
- **Selling Price**: ₹45 per piece (bulk discount)
- **Total Sale**: ₹270
- **Cost**: ₹41.67 × 6 = ₹250.02
- **Profit**: ₹270 - ₹250.02 = ₹19.98
- **Profit Margin**: 8%

#### **Customer C - Premium Customer:**
- **Order**: 2 Pieces
- **Selling Price**: ₹55 per piece (premium pricing)
- **Total Sale**: ₹110
- **Cost**: ₹41.67 × 2 = ₹83.34
- **Profit**: ₹110 - ₹83.34 = ₹26.66
- **Profit Margin**: 32%

## 💻 Database Implementation

### **Batch Record:**
```sql
INSERT INTO batches (
    product_id,
    batch_number,
    purchase_price,
    selling_price,
    quantity_received,
    quantity_remaining,
    purchase_unit_id,
    purchase_quantity,
    cost_per_sale_unit,
    sale_unit_id,
    available_sale_quantity,
    min_selling_price,
    max_selling_price,
    conversion_factor
) VALUES (
    1, -- Product ID
    'SCREW-M8-001',
    500.00, -- Purchase price per box
    50.00, -- Base selling price per piece
    1, -- 1 box received
    1, -- 1 box remaining
    5, -- Box unit ID
    1.00, -- 1 box
    41.67, -- Cost per piece (calculated)
    1, -- Piece unit ID
    12.00, -- 12 pieces available
    45.00, -- Minimum selling price
    55.00, -- Maximum selling price
    12.00 -- 1 box = 12 pieces
);
```

## 🎯 How to Use This System

### **Step 1: Create Batch with Bulk Purchase Data**
1. **Product**: Select M8 Screws
2. **Purchase Unit**: Box
3. **Purchase Quantity**: 1
4. **Purchase Price**: ₹500
5. **Sale Unit**: Pieces
6. **Conversion Factor**: 12
7. **Min Selling Price**: ₹45
8. **Max Selling Price**: ₹55

### **Step 2: System Automatically Calculates**
- **Cost per Piece**: ₹41.67
- **Available Sale Quantity**: 12 pieces
- **Profit Margins**: 8% to 32%

### **Step 3: Sales Process**
1. **Customer Orders**: 3 pieces
2. **Check Stock**: 12 pieces available ✓
3. **Set Price**: ₹50 per piece (customer-specific)
4. **Calculate Profit**: ₹24.99 profit
5. **Update Stock**: 9 pieces remaining

## 📊 Profit Tracking

### **Daily Profit Report:**
```
Product: M8 Screws
Total Sales: 11 pieces
Total Revenue: ₹520
Total Cost: ₹458.37
Total Profit: ₹61.63
Average Profit Margin: 13.4%
```

### **Customer Profitability:**
```
Customer A (Retail): ₹24.99 profit (20% margin)
Customer B (Wholesale): ₹19.98 profit (8% margin)
Customer C (Premium): ₹26.66 profit (32% margin)
```

## 🚀 Benefits of This System

### **For Your Business:**
1. **Accurate Cost Tracking**: Know exact cost per piece
2. **Flexible Pricing**: Different prices for different customers
3. **Profit Optimization**: Track which customers are most profitable
4. **Inventory Management**: Real-time stock tracking in both units

### **For Your Customers:**
1. **Fair Pricing**: Prices based on actual costs
2. **Bulk Discounts**: Better prices for larger orders
3. **Consistent Availability**: Real-time stock information

## 🎯 Next Steps

1. **Test the System**: Create a batch with bulk purchase data
2. **Set Pricing Rules**: Define customer-specific pricing
3. **Track Profits**: Monitor profit margins per sale
4. **Optimize Pricing**: Adjust prices based on profit data

This system will help you manage bulk purchases and individual sales efficiently while maximizing profits!
