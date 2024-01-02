'use client';

import React, { useEffect, useState } from "react";
import {
  PaymentElement,
  useStripe,
  useElements, Elements
} from "@stripe/react-stripe-js";
import { loadStripe } from "@stripe/stripe-js";
import axios from "axios";

function CheckoutForm() {
  const stripe = useStripe();
  const elements = useElements();

  const [message, setMessage] = useState<any>(null);
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    if (!stripe) {
      return;
    }

    const clientSecret = new URLSearchParams(window.location.search).get(
      "payment_intent_client_secret"
    );

    if (!clientSecret) {
      return;
    }

    stripe.retrievePaymentIntent(clientSecret).then(({ paymentIntent }: any) => {
      switch (paymentIntent.status) {
        case "succeeded":
          setMessage("Payment succeeded!");
          break;
        case "processing":
          setMessage("Your payment is processing.");
          break;
        case "requires_payment_method":
          setMessage("Your payment was not successful, please try again.");
          break;
        default:
          setMessage("Something went wrong.");
          break;
      }
    });
  }, [stripe]);

  const handleSubmit = async (e: any) => {
    e.preventDefault();

    if (!stripe || !elements) {
      // Stripe.js hasn't yet loaded.
      // Make sure to disable form submission until Stripe.js has loaded.
      return;
    }

    setIsLoading(true);

    const { error } = await stripe.confirmPayment({
      elements,
      confirmParams: {
        // Make sure to change this to your payment completion page
        return_url: "http://localhost:3000/checkout/success",
      },
    });

    // This point will only be reached if there is an immediate error when
    // confirming the payment. Otherwise, your customer will be redirected to
    // your `return_url`. For some payment methods like iDEAL, your customer will
    // be redirected to an intermediate site first to authorize the payment, then
    // redirected to the `return_url`.
    if (error.type === "card_error" || error.type === "validation_error") {
      setMessage(error.message);
    } else {
      setMessage("An unexpected error occurred.");
    }

    setIsLoading(false);
  };

  const paymentElementOptions: any = {
    layout: "tabs"
  }

  return (
    <form
      id="payment-form"
      onSubmit={handleSubmit}
    >
      <PaymentElement
        id="payment-element"
        options={paymentElementOptions}
      />
      <button
        disabled={isLoading || !stripe || !elements}
        id="submit"
      >
        <span id="button-text">
          {isLoading ? <div
            className="spinner"
            id="spinner"
          ></div> : "Pay now"}
        </span>
      </button>
      {/* Show any error or success messages */}
      {message && <div id="payment-message">{message}</div>}
    </form>
  );
}

export default function Page() {
  console.log('checkout page')
  const stripePromise = loadStripe(
    "pk_test_51IqhrJJv2dzDDjOjDBuSb240GhU920A8zQhCXC6NHYexqcippO3F4gH2KDYznj8Pu4uDwBVFi16r8e31MVnTyhbj00ue2Xp0rI");
  const [clientSecret, setClientSecret] = useState<string>('');
  const appearance = {
    theme: 'stripe',
  };

  useEffect(() => {
    (async () => {
      axios.defaults.withCredentials = true;
      axios.defaults.withXSRFToken = true
      const jsonResult = await axios.post('http://localhost/api/create-payment-intent', {
        "billingAddressId": 4,
        "shippingAddressId": 4,
        "shippingMethodId": 'BASDEL'
      })
      console.log(jsonResult.data);
      setClientSecret(jsonResult.data.clientSecret);
    })();
  }, []);

  const options = {
    clientSecret,
    appearance,
  } as any;
  return clientSecret &&
    <Elements
      stripe={stripePromise}
      options={options}
    >
      <CheckoutForm />
    </Elements>
}
