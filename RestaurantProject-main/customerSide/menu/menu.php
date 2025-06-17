<?php include_once('../components/header.php'); ?>

<!-- Menu Section -->
<section id="menu">
    <div class="menu-header">
        <h1 class="section-title">Our <span>Menu</span></h1>
        <p>Delicious dishes made with fresh, high-quality ingredients</p>
    </div>

    <!-- Menu Filter Buttons -->
    <div class="menu-filter">
        <button class="filter-btn active" data-filter="all">All Items</button>
        <button class="filter-btn" data-filter="main">Main Dishes</button>
        <button class="filter-btn" data-filter="side">Side Dishes</button>
        <button class="filter-btn" data-filter="drink">Drinks</button>
    </div>

    <!-- Menu Grid -->
    <div class="menu-grid">
        <!-- Main Dishes -->
        <?php 
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        foreach ($mainDishes as $item): 
            $imagePath = !empty($item['image']) ? $baseUrl . '/RestaurantProject/RestaurantProject-main/adminSide/uploads/' . $item['image'] : 'https://source.unsplash.com/random/600x400/?food,' . urlencode($item['item_name']);
        ?>
        <div class="menu-item" data-category="main">
            <div class="menu-item-img" style="background-image: url('<?php echo $imagePath; ?>');">
            </div>
            <div class="menu-item-details">
                <span class="menu-item-category">Main Dish</span>
                <div class="menu-item-header">
                    <h3 class="menu-item-name"><?php echo htmlspecialchars($item['item_name']); ?></h3>
                    <span class="menu-item-price">RM<?php echo number_format($item['item_price'], 2); ?></span>
                </div>
                <p class="menu-item-desc">Delicious <?php echo htmlspecialchars($item['item_name']); ?> made with fresh ingredients and special spices.</p>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Side Dishes -->
        <?php 
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        foreach ($sides as $item): 
            $imagePath = !empty($item['image']) ? $baseUrl . '/RestaurantProject/RestaurantProject-main/adminSide/uploads/' . $item['image'] : 'https://source.unsplash.com/random/600x400/?side,dish,' . urlencode($item['item_name']);
        ?>
        <div class="menu-item" data-category="side">
            <div class="menu-item-img" style="background-image: url('<?php echo $imagePath; ?>');">
            </div>
            <div class="menu-item-details">
                <span class="menu-item-category">Side Dish</span>
                <div class="menu-item-header">
                    <h3 class="menu-item-name"><?php echo htmlspecialchars($item['item_name']); ?></h3>
                    <span class="menu-item-price">RM<?php echo number_format($item['item_price'], 2); ?></span>
                </div>
                <p class="menu-item-desc">Perfect side of <?php echo htmlspecialchars($item['item_name']); ?> to complement your meal.</p>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Drinks -->
        <?php 
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        foreach ($drinks as $item): 
            $imagePath = !empty($item['image']) ? $baseUrl . '/RestaurantProject/RestaurantProject-main/adminSide/uploads/' . $item['image'] : 'https://source.unsplash.com/random/600x400/?drink,' . urlencode($item['item_name']);
        ?>
        <div class="menu-item" data-category="drink">
            <div class="menu-item-img" style="background-image: url('<?php echo $imagePath; ?>');">
            </div>
            <div class="menu-item-details">
                <span class="menu-item-category">Drink</span>
                <div class="menu-item-header">
                    <h3 class="menu-item-name"><?php echo htmlspecialchars($item['item_name']); ?></h3>
                    <span class="menu-item-price">RM<?php echo number_format($item['item_price'], 2); ?></span>
                </div>
                <p class="menu-item-desc">Refreshing <?php echo htmlspecialchars($item['item_name']); ?> to quench your thirst.</p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>



<!-- Add JavaScript for filtering -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const menuItems = document.querySelectorAll('.menu-item');

    // Filter items when a button is clicked
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            // Show/hide menu items
            menuItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Initialize with all items shown
    filterButtons[0].click();
});
</script>

<?php include_once('../components/footer.php'); ?>