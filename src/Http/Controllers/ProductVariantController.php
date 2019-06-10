<?php

namespace Sitec\Commerce\Http\Controllers;

use Cms;
use Response;
use Illuminate\Http\Request;
use Sitec\Commerce\Models\Products;
use Sitec\Cms\Controllers\SitecCmsController;
use Sitec\Commerce\Repositories\ProductRepository;
use Sitec\Commerce\Repositories\ProductVariantRepository;

class ProductVariantController extends SitecCmsController
{
    /**
     * Product Repository.
     *
     * @var Sitec\Commerce\Repositories\ProductRepository
     */
    public $productRepository;

    /**
     * Product Variant Repository.
     *
     * @var Sitec\Commerce\Repositories\ProductVariantRepository
     */
    public $productVariantRepository;

    public function __construct(
        ProductVariantRepository $productVariantRepository,
        ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
    }

    /**
     * Get a product's variants.
     *
     * @param int                     $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function variants($id, Request $request)
    {
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            Cms::notification('Product not found', 'warning');

            return redirect(route('cms.products.index'));
        }

        if ($this->productVariantRepository->addVariant($product, $request->all())) {
            Cms::notification('Variant successfully added.', 'success');
        } else {
            Cms::notification('Failed to add variant. Missing Key or Value.', 'warning');
        }

        return redirect(route(config('cms.backend-route-prefix', 'cms').'.products.edit', $id).'?tab=variants');
    }

    /**
     * Save a variant.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function saveVariant(Request $request)
    {
        $this->productVariantRepository->saveVariant($request->all());

        return Response::json(['success']);
    }

    /**
     * Delete a variant.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function deleteVariant(Request $request)
    {
        $this->productVariantRepository->deleteVariant($request->all());

        return Response::json(['success']);
    }
}
