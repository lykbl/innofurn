'use client';

import { useEffect } from "react";
import axios from "axios";
import { redirect } from "next/navigation";

function getCookie(cookieName: string) {
  const cookies = document.cookie.split(';');

  for (let i = 0; i < cookies.length; i++) {
    const cookie = cookies[i].trim();

    // Check if this cookie is the one we're looking for
    if (cookie.startsWith(cookieName + '=')) {
      // Extract and return the cookie value
      return cookie.substring(cookieName.length + 1);
    }
  }

  // If the cookie is not found, return null or an appropriate default value
  return null;
}

export default function Page() {
  useEffect(() => {
    (async () => {
      axios.defaults.withCredentials = true;
      axios.defaults.withXSRFToken = true
      const response = await axios.get('http://localhost/sanctum/csrf-cookie');
      const loginResponse = await axios.post('http://localhost/login', {
        'email': 'test04@gmail.com',
        'password': '12345678!',
      }, {
        withCredentials: true,
      } as any);

      redirect('/checkout', 'push' as any);
    })();
  }, []);
  return (
    <>
    </>
  );
}
