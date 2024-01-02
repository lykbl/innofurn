/** @type {import('next').NextConfig} */
const path = require('path')
const nextConfig = {
  reactStrictMode: false, // TODO toggle me back
  sassOptions: {
    // includePaths: [path.join(__dirname, 'path', 'styles')],
  },
}

module.exports = nextConfig
