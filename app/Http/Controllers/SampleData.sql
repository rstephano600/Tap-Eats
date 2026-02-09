
INSERT INTO menu_categories
(supplier_id,category_name,description,display_order,is_active,created_by,updated_by,status,created_at,updated_at
)
VALUES
(NULL, 'Chips & Fries', 'French fries, chips mayai and related sides', 1, 1, 1, 1, 'active', NOW(), NOW()),
(NULL, 'Nyama Choma', 'Grilled beef, goat and roasted meat', 2, 1, 1, 1, 'active', NOW(), NOW()),
(NULL, 'Rice Dishes', 'Wali wa kawaida, wali wa nazi, wali biriani', 3, 1, 1, 1, 'active', NOW(), NOW()),
(NULL, 'Chicken Dishes', 'Fried chicken, grilled chicken, kuku wa kuchoma', 4, 1, 1, 1, 'active', NOW(), NOW()),
(NULL, 'Beef Dishes', 'Beef stew, beef fry and traditional beef meals', 5, 1, 1, 1, 'active', NOW(), NOW()),
(NULL, 'Fish & Seafood', 'Samaki wa kukaanga, wa kuchoma na dagaa', 6, 1, 1, 1, 'active', NOW(), NOW()),
(NULL, 'Local Traditional Foods', 'Ugali, ndizi, mihogo, mboga za asili', 7, 1, 1, 1, 'active', NOW(), NOW()),
(NULL, 'Breakfast', 'Chapati, maandazi, uji and breakfast meals', 8, 1, 1, 1, 'active', NOW(), NOW()),
(NULL, 'Snacks', 'Samosa, vitumbua, viazi karai', 9, 1, 1, 1, 'active', NOW(), NOW()),
(NULL, 'Drinks', 'Soft drinks, juices, water and hot drinks', 10, 1, 1, 1, 'active', NOW(), NOW()),
(NULL, 'Desserts', 'Ice cream, fruits and sweet dishes', 11, 1, 1, 1, 'active', NOW(), NOW());


INSERT INTO menu_items
(supplier_id,menu_category_id,name,slug,description,price,discounted_price,image_url,gallery_images,created_at,updated_at
)
VALUES
-- ======================
-- CHIPS & FRIES (1)
-- ======================
(13, 1, 'Chips Mayai', 'chips-mayai', 'French fries mixed with eggs', 6000, NULL, NULL, NULL, NOW(), NOW()),
(13, 1, 'Plain Chips', 'plain-chips', 'Deep fried potato chips', 4000, NULL, NULL, NULL, NOW(), NOW()),
(13, 1, 'Chips Kuku', 'chips-kuku', 'Chips served with fried chicken', 9000, NULL, NULL, NULL, NOW(), NOW()),

-- ======================
-- NYAMA CHOMA (2)
-- ======================
(14, 2, 'Nyama Choma Beef', 'nyama-choma-beef', 'Grilled beef nyama choma', 15000, NULL, NULL, NULL, NOW(), NOW()),
(14, 2, 'Nyama Choma Mbuzi', 'nyama-choma-mbuzi', 'Grilled goat meat', 18000, NULL, NULL, NULL, NOW(), NOW()),
(14, 2, 'Mixed Grill', 'mixed-grill', 'Beef and goat mixed grill', 20000, NULL, NULL, NULL, NOW(), NOW()),

-- ======================
-- RICE DISHES (3)
-- ======================
(13, 3, 'Wali Maharage', 'wali-maharage', 'Rice served with beans stew', 5000, NULL, NULL, NULL, NOW(), NOW()),
(13, 3, 'Wali Kuku', 'wali-kuku', 'Rice with chicken stew', 8000, NULL, NULL, NULL, NOW(), NOW()),
(13, 3, 'Pilau Beef', 'pilau-beef', 'Spiced pilau rice with beef', 9000, NULL, NULL, NULL, NOW(), NOW()),

-- ======================
-- CHICKEN DISHES (4)
-- ======================
(1, 4, 'Fried Chicken', 'fried-chicken', 'Deep fried crispy chicken', 7000, NULL, NULL, NULL, NOW(), NOW()),
(1, 4, 'Grilled Chicken', 'grilled-chicken', 'Charcoal grilled chicken', 8000, NULL, NULL, NULL, NOW(), NOW()),
(1, 4, 'Chicken Stew', 'chicken-stew', 'Traditional chicken stew', 7500, NULL, NULL, NULL, NOW(), NOW()),

-- ======================
-- BEEF DISHES (5)
-- ======================
(10, 5, 'Beef Fry', 'beef-fry', 'Fried beef with onions', 7000, NULL, NULL, NULL, NOW(), NOW()),
(10, 5, 'Beef Stew', 'beef-stew', 'Slow cooked beef stew', 7500, NULL, NULL, NULL, NOW(), NOW()),
(10, 5, 'Beef Wet Fry', 'beef-wet-fry', 'Spicy wet beef fry', 8000, NULL, NULL, NULL, NOW(), NOW()),

-- ======================
-- FISH & SEAFOOD (6)
-- ======================
(1, 6, 'Fried Fish', 'fried-fish', 'Deep fried fresh fish', 9000, NULL, NULL, NULL, NOW(), NOW()),
(1, 6, 'Grilled Fish', 'grilled-fish', 'Charcoal grilled fish', 10000, NULL, NULL, NULL, NOW(), NOW()),
(1, 6, 'Dagaa', 'dagaa', 'Traditional fried dagaa', 6000, NULL, NULL, NULL, NOW(), NOW()),

-- ======================
-- LOCAL TRADITIONAL (7)
-- ======================
(13, 7, 'Ugali Beef', 'ugali-beef', 'Ugali served with beef stew', 7000, NULL, NULL, NULL, NOW(), NOW()),
(13, 7, 'Ugali Samaki', 'ugali-samaki', 'Ugali with fish', 8000, NULL, NULL, NULL, NOW(), NOW()),
(13, 7, 'Ndizi Nyama', 'ndizi-nyama', 'Cooked bananas with meat', 7500, NULL, NULL, NULL, NOW(), NOW()),

-- ======================
-- BREAKFAST (8)
-- ======================
(14, 8, 'Chapati', 'chapati', 'Freshly made chapati', 1000, NULL, NULL, NULL, NOW(), NOW()),
(14, 8, 'Maandazi', 'maandazi', 'Soft fried dough', 1500, NULL, NULL, NULL, NOW(), NOW()),
(14, 8, 'Uji', 'uji', 'Traditional porridge', 2000, NULL, NULL, NULL, NOW(), NOW()),

-- ======================
-- SNACKS (9)
-- ======================
(11, 9, 'Samosa', 'samosa', 'Beef samosa', 1000, NULL, NULL, NULL, NOW(), NOW()),
(11, 9, 'Viazi Karai', 'viazi-karai', 'Deep fried coated potatoes', 3000, NULL, NULL, NULL, NOW(), NOW()),
(11, 9, 'Vitumbua', 'vitumbua', 'Rice flour snacks', 2000, NULL, NULL, NULL, NOW(), NOW()),

-- ======================
-- DRINKS (10)
-- ======================
(10, 10, 'Soda', 'soda', 'Assorted soft drinks', 1000, NULL, NULL, NULL, NOW(), NOW()),
(10, 10, 'Fresh Juice', 'fresh-juice', 'Freshly blended juice', 3000, NULL, NULL, NULL, NOW(), NOW()),
(10, 10, 'Water', 'water', 'Bottled drinking water', 500, NULL, NULL, NULL, NOW(), NOW()),

-- ======================
-- DESSERTS (11)
-- ======================
(1, 11, 'Ice Cream', 'ice-cream', 'Vanilla ice cream scoop', 3000, NULL, NULL, NULL, NOW(), NOW()),
(1, 11, 'Fruit Salad', 'fruit-salad', 'Mixed fresh fruits', 3500, NULL, NULL, NULL, NOW(), NOW()),
(1, 11, 'Cake Slice', 'cake-slice', 'Slice of sponge cake', 4000, NULL, NULL, NULL, NOW(), NOW());
