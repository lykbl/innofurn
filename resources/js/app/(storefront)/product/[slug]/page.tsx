import Breadcrumb from "@/app/ui/common/breadcrumb";
import React from "react";
import ProductDetails from "@/app/ui/storefront/product/product-details";
import MoreFromCreator from "@/app/ui/storefront/product/more-from-creator";
import Reviews from "@/app/ui/storefront/product/reviews";

export default function Page() {
  return (
    <div className='flex flex-col gap-2'>
      <Breadcrumb/>
      <ProductDetails />
      <MoreFromCreator />
      <Reviews />
    </div>
  );
}
