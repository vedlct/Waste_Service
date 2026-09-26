const isStaticExport = process.env.STATIC_EXPORT === 'true'
const basePath = (process.env.NEXT_PUBLIC_BASE_PATH || '').replace(/\/+$/, '')

/** @type {import('next').NextConfig} */
const nextConfig = {
  ...(isStaticExport ? { output: 'export', trailingSlash: true } : {}),
  ...(basePath ? { basePath } : {}),
  images: {
    qualities: [75, 90],
    ...(isStaticExport ? { loader: 'custom', loaderFile: './src/lib/imageLoader.js' } : {}),
  },
  reactCompiler: true,
};

export default nextConfig;
