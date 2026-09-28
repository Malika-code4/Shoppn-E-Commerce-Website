<?php

require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

require_admin();

$productController = new ProductController();

$brands = $productController->getAllBrands();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Brands - Shoppn</title>

    <link rel="stylesheet" href="../../css/style.css">

</head>

<body>

<?php require_once __DIR__ . '/../layout/header.php'; ?>


<div class="container">

    <h2>Manage Brands</h2>


    <?php if (isset($_SESSION['success'])): ?>

        <p class="success">

            <?php

            echo htmlspecialchars($_SESSION['success']);

            unset($_SESSION['success']);

            ?>

        </p>

    <?php endif; ?>


    <?php if (isset($_SESSION['error'])): ?>

        <p class="error">

            <?php

            echo htmlspecialchars($_SESSION['error']);

            unset($_SESSION['error']);

            ?>

        </p>

    <?php endif; ?>


    <h3>Add New Brand</h3>


    <form
        action="../../actions/add_brand_action.php"
        method="POST"
    >

        <div>

            <label for="brand_name">
                Brand Name
            </label>

            <input
                type="text"
                id="brand_name"
                name="brand_name"
                maxlength="100"
                required
            >

        </div>


        <button type="submit">
            Add Brand
        </button>

    </form>


    <h3>Existing Brands</h3>


    <?php if (!empty($brands)): ?>

        <table border="1">

            <thead>

                <tr>
                    <th>ID</th>
                    <th>Brand Name</th>
                    <th>Action</th>
                </tr>

            </thead>


            <tbody>

                <?php foreach ($brands as $brand): ?>

                    <tr>

                        <td>
                            <?php echo (int) $brand['brand_id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($brand['brand_name']); ?>
                        </td>

                        <td>

                            <a
                                href="brand.php?edit_id=<?php echo (int) $brand['brand_id']; ?>"
                            >
                                Edit
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php else: ?>

        <p>No brands available.</p>

    <?php endif; ?>


</div>


<?php require_once __DIR__ . '/../layout/footer.php'; ?>


</body>

</html>