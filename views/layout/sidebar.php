<?php

require_once __DIR__ . '/../../controllers/ProductController.php';

$productController = new ProductController();

$categories = $productController->getAllCategories();
$brands = $productController->getAllBrands();

?>

<aside>

    <section>
        <h3>Categories</h3>

        <?php if (!empty($categories)): ?>

            <ul>
                <?php foreach ($categories as $category): ?>

                    <li>
                        <a href="../../index.php?cat=<?php echo (int) $category['cat_id']; ?>">
                            <?php echo htmlspecialchars($category['cat_name']); ?>
                        </a>
                    </li>

                <?php endforeach; ?>
            </ul>

        <?php else: ?>

            <p>No categories available.</p>

        <?php endif; ?>
    </section>


    <section>
        <h3>Brands</h3>

        <?php if (!empty($brands)): ?>

            <ul>
                <?php foreach ($brands as $brand): ?>

                    <li>
                        <a href="../../index.php?brand=<?php echo (int) $brand['brand_id']; ?>">
                            <?php echo htmlspecialchars($brand['brand_name']); ?>
                        </a>
                    </li>

                <?php endforeach; ?>
            </ul>

        <?php else: ?>

            <p>No brands available.</p>

        <?php endif; ?>

    </section>

</aside>