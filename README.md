# Image Redirect Extension

This is the Image Redirect Extension.for phpBB.

It rewrites the links for externally hosted images to point to a local image cache and/or a secure proxy (camo) server. The local image cache can be a mount point for [Azure BlobFuse](https://github.com/Azure/azure-storage-fuse) or [Mountpoint for Amazon S3](https://docs.aws.amazon.com/AmazonS3/latest/userguide/mountpoint.html) enabling the photos to be stored and access directly from Azure Blob Storage/AWS S3. 

## Quick Install
You can install this on phpBB 3.2.x by following the steps below:

1. In the `ext` directory of your phpBB board, create a new directory named `v12mike` (if it does not already exist) and navigate to it
2. Copy the 'imageredirect' directory into the 'v12mike' directory (the extension tree must now be under `ext/v12mike/imageredirect`)
3. Navigate in the ACP to `Customise -> Manage extensions`.
4. Look for `Image Redirect` under the phpBB Disabled Extensions list, and click its `Enable` link.
5. To use the (optional) local hosting of external images feature
    * You MUST have already downloaded the external image files using the scripts https://github.com/v12mike/fetch-external-images
    * Navigate in the phpBB ACP to 'Extensions -> Image Redirect -> Local Image Store Settings'.
    * Enter the path to the local image file store (e.g. `images/ext/`)
    * Enable local store mode
6. To use the (optional) Base URL for Images feature
    * You MUST have followed the steps in #5 above.
    * The `images/ext` folder must be a symbolic link to your [Azure BlobFuse](https://github.com/Azure/azure-storage-fuse) mount.
    * Navigate in the phpBB ACP to 'Extensions -> Image Redirect -> Local Image Store Settings'.
    * Enter the path to the Base URL for Images. This is the URL to your Blob Container/S3 Bucket. Include the URL scheme (e.g. `https://myforum.z13.web.core.windows.net/external`).
    * NOTE: [Mountpoint for Amazon S3](https://docs.aws.amazon.com/AmazonS3/latest/userguide/mountpoint.html) should work the same way, but has not been tested.
7. To use the (optional) secure image proxy feature
    * You must already have a Camo proxy server (https://github.com/atmos/camo) or other secure proxy server available.
    * Navigate in the ACP to 'Extensions -> Image Redirect -> Proxy Settings'.
    * Enter the proxy address (without protocol specifier or trailing `/`) e.g. `mydomain.com/camo`
    * Enter the camo API key (if using a camo server) 
    * Select 'Camo Mode' or 'Simple Mode' and that 'Image Proxy Enable' is selected.

## Upgrading from v1.0.x
 * Version 2.x of this extension is NOT compatible with phpBB v3.1.x
 * Disable the v1.0.x extension before deleting the extension files
 * Delete all of the directories and files in the 'ext/v12mike/imageredirect' directory
 * Copy all of the v2.0.x files into their respective directories under 'ext/v12mike/imageredirect'
 * Enable the Image Redirect extension in the ACP.  The settings should not need to be adjusted.
 
## Support

* Report bugs and other issues via github.

## License
[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)

