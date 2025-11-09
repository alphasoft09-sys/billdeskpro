# Hardware Shop Business Strategy: Bulk Purchase & Individual Sales

## 🎯 Business Scenario Analysis

### **Your Current Challenge:**
- **Purchase**: Buy products in bulk (boxes, cartons, packs)
- **Storage**: Store in bulk units
- **Sales**: Sell individual pieces at different prices
- **Profit**: Need to calculate profit per piece from bulk purchase

### **Example Scenario:**
```
Purchase: 1 Box of Screws (₹500)
Box Contains: 12 Pieces
Cost per Piece: ₹500 ÷ 12 = ₹41.67

Sales Options:
- Customer A: ₹45 per piece (8% profit)
- Customer B: ₹50 per piece (20% profit)  
- Customer C: ₹55 per piece (32% profit)
```

## 🏗️ System Architecture Strategy

### **1. Multi-Level Unit System**
```
Primary Unit (Purchase): Box
Secondary Unit (Storage): Box
Tertiary Unit (Sales): Pieces

Conversion: 1 Box = 12 Pieces
```

### **2. Pricing Strategy Options**

#### **Option A: Cost-Plus Pricing**
- Calculate cost per piece from bulk purchase
- Add fixed markup percentage
- Apply to all sales

#### **Option B: Market-Based Pricing**
- Set different prices for different customers
- Track profit margins per sale
- Maintain minimum profit thresholds

#### **Option C: Dynamic Pricing**
- Base price + customer-specific adjustments
- Bulk discount tiers
- Premium pricing for urgent orders

### **3. Inventory Management Flow**

#### **Purchase Process:**
1. **Receive Bulk Order**: 1 Box of Screws
2. **Record Purchase Price**: ₹500 per box
3. **Calculate Unit Cost**: ₹41.67 per piece
4. **Store in Inventory**: 1 Box = 12 Pieces available

#### **Sales Process:**
1. **Customer Orders**: 3 Pieces of Screws
2. **Check Inventory**: 12 Pieces available
3. **Calculate Price**: ₹50 per piece (customer-specific)
4. **Update Stock**: 12 - 3 = 9 Pieces remaining
5. **Record Sale**: ₹150 total, ₹25.50 profit

## 💻 Technical Implementation

### **Database Structure Enhancement:**

#### **Enhanced Batch Table:**
```sql
batches:
- purchase_unit_id (Box)
- purchase_quantity (1)
- purchase_price_per_unit (₹500)
- cost_per_sale_unit (₹41.67)
- sale_unit_id (Pieces)
- available_sale_quantity (12)
- min_selling_price (₹45)
- max_selling_price (₹55)
```

#### **Pricing Rules Table:**
```sql
pricing_rules:
- batch_id
- customer_type (retail, wholesale, premium)
- markup_percentage
- fixed_price
- min_quantity
- max_quantity
```

### **Business Logic:**

#### **Purchase Calculation:**
```php
function calculateUnitCost($purchasePrice, $purchaseQuantity, $conversionFactor) {
    return $purchasePrice / ($purchaseQuantity * $conversionFactor);
}

// Example: ₹500 / (1 * 12) = ₹41.67 per piece
```

#### **Sales Pricing:**
```php
function calculateSellingPrice($unitCost, $markupPercentage, $customerType) {
    $basePrice = $unitCost * (1 + $markupPercentage);
    return applyCustomerDiscount($basePrice, $customerType);
}
```

## 📊 Profit Tracking Strategy

### **Profit Calculation:**
```
Sale Price: ₹50 per piece
Cost Price: ₹41.67 per piece
Profit: ₹8.33 per piece (20% margin)
```

### **Reporting:**
- **Daily Profit Report**: Track profit per product
- **Customer Profitability**: Which customers are most profitable
- **Product Performance**: Which products have best margins
- **Inventory Turnover**: How quickly stock moves

## 🎯 Recommended Implementation Plan

### **Phase 1: Enhanced Batch System**
1. Add unit conversion to batch creation
2. Calculate cost per sale unit automatically
3. Set minimum/maximum selling prices

### **Phase 2: Pricing Rules Engine**
1. Create customer-specific pricing rules
2. Implement markup percentage system
3. Add bulk discount tiers

### **Phase 3: Advanced Reporting**
1. Profit margin tracking
2. Customer profitability analysis
3. Inventory turnover reports

### **Phase 4: Automation**
1. Auto-calculate prices based on rules
2. Suggest optimal pricing strategies
3. Alert for low-profit sales

## 🚀 Benefits of This Strategy

### **For Your Business:**
1. **Accurate Profit Tracking**: Know exact profit per sale
2. **Flexible Pricing**: Different prices for different customers
3. **Inventory Optimization**: Track what sells best
4. **Cost Control**: Monitor purchase vs. selling costs

### **For Your Customers:**
1. **Competitive Pricing**: Fair prices based on costs
2. **Bulk Discounts**: Better prices for larger orders
3. **Consistent Service**: Reliable pricing and availability

## 📈 Success Metrics

### **Key Performance Indicators:**
- **Average Profit Margin**: Target 15-25%
- **Inventory Turnover**: How quickly stock sells
- **Customer Retention**: Repeat business percentage
- **Cost Efficiency**: Purchase cost optimization

This strategy will help you manage bulk purchases and individual sales efficiently while maximizing profits and maintaining customer satisfaction.
