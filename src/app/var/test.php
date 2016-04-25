<?php

$data = ['result' => [
    'product' => [],
    'category' => [],
    ]
];

require_once './data.php';

require_once 'Mage.php';
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

ini_set('display_errors', 1);
ini_set('max_execution_time', 600);

/**
 * @param $dryrun
 * @param $data
 */
function runImport($dryrun, $data, $type)
{
    echo str_repeat('-', 30) . PHP_EOL;
    echo "Run import for " . $type . PHP_EOL;
    echo "Dryrun: " . ( $dryrun ? 'on' : 'off' ) . PHP_EOL;

    $start = microtime(true);

    try {
        /** @var $import AvS_FastSimpleImport_Model_Import */
        $import = Mage::getModel('fastsimpleimport/import');

        if ($dryrun) {
            switch ($type) {
                case 'product':
                    $result = $import->dryrunProductImport($data);
                    break;

                case 'category':
                    $result = $import->dryrunCategoryImport($data);
                    break;

                case 'customer':
                    $result = $import->dryrunCustomerImport($data);
                    break;

                default:
                    break;
            }

            echo($result ? 'Input is OK' : 'Input has Errors') . PHP_EOL;

            if (is_object($import)) {
                echo "Messages: ";
                echo $import->getErrorMessage() . PHP_EOL;
            }
        } else {
            switch ($type) {
                case 'product':
                    $import->processProductImport($data);
                    break;

                case 'category':
                    $import->processCategoryImport($data, Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE);
                    break;

                case 'categoryProducts':
                    $import->processCategoryProductImport($data, Mage_ImportExport_Model_Import::BEHAVIOR_REPLACE);
                    break;

                case 'customer':
                    $import->processCustomerImport($data);
                    break;

                default:
                    break;
            }
        }

        echo 'Elapsed time: ' . round(microtime(true) - $start, 2) . 's' . "\n";
    } catch (Exception $e) {
        var_dump($e);
        print_r($import->getErrorMessages());
    }
}

/**
 * @param $product
 * @param $categories
 * @return array
 */
function stripIdFieldFromCategories($categories)
{
    $categoriesTmp1 = [];
    foreach ($categories as $key => $category) {
        unset($category['id']);
        unset($category['parent_id']);
        $categoriesTmp1[$category['_category']] = $category;
    }

    $categoriesTmp2 = [];
    foreach ($categoriesTmp1 as $category) {
        $categoriesTmp2[] = $category;
    }

    return $categoriesTmp2;
}

/**
 * @param $product
 * @param $categories
 * @return array
 */
function getCategoriesForProduct($product) {
    $categoryProductMapping = array();
//    $categoryIds = explode(',', $product['category_ids']);

    $categoryPositions = [];

    foreach ($product['categories'] as $category) {
        $categoryProductMapping[] = array(
            '_root' => 'Default Category',
            '_category' => $category,
            '_sku' => $product['sku'],
            'position' => $categoryPositions[$category]++,
        );
    }

    return $categoryProductMapping;
}

$time = microtime(true);

$products = $data['result']['product'];
$categories = $data['result']['category'];

$dryrun = false;
//$dryrun = true;

runImport($dryrun, $products, 'product');
runImport($dryrun, $categories, 'category');
//runImport($dryrun, stripIdFieldFromCategories($categories), 'category');

$mapping = array();

$countProducts = count($products);
$len = strlen(strval($countProducts));
echo sprintf('Processing %d products: %s', $countProducts, str_repeat('0', $len));

foreach ($products as $product) {
    echo "\033[" . $len . "D";      // Move 5 characters backward
    echo str_pad($count++, $len, '0', STR_PAD_LEFT);
    $mapping = array_merge($mapping, getCategoriesForProduct($product));
}

echo PHP_EOL;

//file_put_contents('testmapping.php', '<?php $mapping =' . var_export($mapping, true) . ';');
//include_once 'testmapping.php';

runImport($dryrun, $mapping, 'categoryProducts');

echo str_repeat('-', 30) . PHP_EOL;
echo 'Total elapsed time: ' . round(microtime(true) - $time, 2) . 's' . "\n";
