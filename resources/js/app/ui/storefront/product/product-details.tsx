'use client';

import React, { useState } from "react";
import Variants, { Variant } from "@/app/ui/storefront/product/variants";
import Breadcrumb from "@/app/ui/common/breadcrumb";
import Image from "next/image";
import Accordion from "@/app/ui/animated/accordion";
import Link from "@/app/ui/common/link";
import { Button } from "@/app/ui/common/button";
import { FaPhoneAlt } from "react-icons/fa";
import { IoChatboxEllipses } from "react-icons/io5";
import Rating from "@/app/ui/storefront/product/rating";
import ExtraOffers from "@/app/ui/storefront/product/extra-offers";
import CartControl from "@/app/ui/storefront/product/cart-control";

const images = [
  {
    'href': '/sample-kitchen-image-2.jpg',
    'alt': 'Image',
  },
  {
    'href': '/sample-kitchen-image-2.jpg',
    'alt': 'Image',
  },
  {
    'href': '/sample-kitchen-image-2.jpg',
    'alt': 'Image',
  },
  {
    'href': '/sample-kitchen-image-2.jpg',
    'alt': 'Image',
  },
];

const variantOptions: Variant[] = [
  {
    id: 1,
    name: 'Black',
    href: '/sample-kitchen-image-1.jpg',
    price: 10.00,
  },
  {
    id: 2,
    name: 'White',
    href: '/sample-kitchen-image-1.jpg',
    price: 12.00,
  },
  {
    id: 3,
    name: 'Purple',
    href: '/sample-kitchen-image-1.jpg',
    price: 8.00,
  },
];
const ProductDetails = () => {
    const [selectedOption, setSelectedOption] = useState<Variant>(variantOptions[0]);
  const selectOption = (id: number) => setSelectedOption(
    variantOptions.find((option) => option.id === id) || variantOptions[0]
  );

  return (
      <div className='flex gap-4'>
        <div className='w-1/2'>
          <div className='flex gap-[20px] flex-wrap'>
            {images.map(({href, alt}, index) =>
              <div key={index}>
                <Image
                  width={364}
                  height={364}
                  src={href}
                  alt={alt}
                  className='rounded'
                />
              </div>
            )}
          </div>
          <div>
            <Accordion>
              <Accordion.Toggle className='text-lg p-2'>Description</Accordion.Toggle>
              <Accordion.Content className='p-2'>
                <div>
                  Create a warm, homey feeling with deep primitive colors in a curl-resistant jute blend that adds style
                  and safety anywhere a small accent rug is needed. The Kettle Grove Jute Rug measures 16.5x33 inches
                  and features a classic half circle shape and tight braids constructed with a durable jute blend. The
                  special blend relaxes faster and stays flat. The included PVC mat makes sure your beautifully braided
                  jute rug stays exactly where it should. The unique shape makes this rug the perfect fit for doorways
                  and in front of kitchen sinks. Features a blend of warm caramel, country black and dark crème with
                  primitive-style stars stenciled in black for a natural, rustic look that complements a wide array of
                  country farmhouse decor.
                  Features
                  <ol>
                    <li>Includes one 16.5 by 33 inch half circle area rug and one PVC rug pad for added traction to hold
                      your rug in place.
                    </li>
                    <li>This rustic area rug is constructed with tightly woven braids made of 80 percent jute and 20
                      percent cotton and other fibers for a heavier rug that relaxes faster and stays flat.
                    </li>
                    <li>Warm, natural tones form the perfect accent piece for modern primitive, classic country and
                      rustic cabin decor.
                    </li>
                    <li>Alternating braids of warm caramel and country black with deep creme highlights are surrounded
                      by a ring of primitive-style stars stenciled black to add rustic, durable style to any surface.
                    </li>
                    <li>Spot clean with a damp cloth and a mild, clear detergent.</li>
                    <li>The Kettle Grove Collection by VHC captures the cozy, rustic feel of modern primitive decor with
                      country black, warm khaki and dark crème designs in a mix of traditional patterns. Primitive
                      five-point stars and crow accent pieces feel at home in traditional country homes and backcountry
                      cabins.
                    </li>
                    <li>Due to the difference of monitor colors, some rug colors may vary slightly. We try to represent
                      all rug colors as accurately as possible.
                    </li>
                  </ol>
                </div>
              </Accordion.Content>
            </Accordion>
            <Accordion>
              <Accordion.Toggle className='text-lg p-2'>Specifications</Accordion.Toggle>
              <Accordion.Content className='p-2'>
                <div>
                  <div className='flex gap-2'>
                    <p>Additional Documents</p>
                    <Link href='/' className='hover:text-blue-500 hover:underline'>Owner Manual (pdf)</Link>
                  </div>
                  <p>Features: </p>
                  <table className='table-auto bg-neutral-100 w-full'>
                    <tbody>
                      <tr>
                        <td> Base Style</td>
                        <td> Saddle</td>
                      </tr>
                      <tr>
                        <td> Number of Stools Included</td>
                        <td> 1</td>
                      </tr>
                      <tr>
                        <td> Base Color (Gray Color)</td>
                        <td> Gray</td>
                      </tr>
                      <tr>
                        <td> Seat Color (Gray Color)</td>
                        <td> Gray</td>
                      </tr>
                      <tr>
                        <td> Frame Material</td>
                        <td> Solid Wood</td>
                      </tr>
                      <tr>
                        <td> Frame Material Details</td>
                        <td> Acacia</td>
                      </tr>
                      <tr>
                        <td> Seat Material</td>
                        <td> Upholstered</td>
                      </tr>
                      <tr>
                        <td> Seat Material Details</td>
                        <td> Faux leather/ Linen</td>
                      </tr>
                      <tr>
                        <td> Natural Variation Type</td>
                        <td> Natural Wood Grain Color Variation</td>
                      </tr>
                      <tr>
                        <td> Back Style</td>
                        <td> Backless</td>
                      </tr>
                      <tr>
                      <td> Upholstered</td>
                      <td> Yes</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </Accordion.Content>
            </Accordion>
          </div>
          <div className='flex justify-between items-center'>
            <div className='text-lg flex-col'>
              <p className='font-semibold'>Have a question?</p>
              <p className='font-normal'>We're here to help.</p>
            </div>
            <div className='flex gap-2'>
              <Button
                className='flex p-2 text-blue-500 border-blue-500 hover:border-blue-700 hover:text-blue-700 gap-1'>
                <FaPhoneAlt size={24}/>
                <span>Call Us</span>
              </Button>
              <Button
                className='flex p-2 text-blue-500 border-blue-500 hover:border-blue-700 hover:text-blue-700 gap-1'>
                <IoChatboxEllipses size={24}/>
                <span>Call Us</span>
              </Button>
            </div>
          </div>
        </div>
        <div className='flex flex-col gap-2 w-1/2 sticky'>
          <h2 className='text-[32px]'>Orchard Hill Faux Sheepskin White Rug</h2>
          <div className='flex gap-1 text-xl'>
            <p className=''>See more by</p>
            <Link
              href='/brand/mercer41'
              className='hover:underline hover:text-blue-500'
            >
              Mercer41
            </Link>
          </div>
          <Rating/>
          <Variants type='colour' options={variantOptions} selectedOption={selectedOption} handleSelect={selectOption}/>
          <ExtraOffers/>
          <CartControl price={selectedOption.price}/>
        </div>
      </div>
  );
}

export default ProductDetails;
