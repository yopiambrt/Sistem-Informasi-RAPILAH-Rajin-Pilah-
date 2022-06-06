/**
* @api {get} /images?name=:nama_foto&path=:path_foto 1.Mendapatkan Foto Berita
* @apiVersion 0.1.0
* @apiName getImage
* @apiGroup Images
* @apiPermission public
*
* @apiDescription digunakan untuk mendapatkan images/gambar dari foto yang ada pada direktori server.
*
* @apiExample Cara penggunaan:
* http://api.linksaya.com/images?name=siraman.jpg&path=./images/2017/11/07
*
* @apiParam {string} nama_foto nama foto sesuai di record berita
* @apiParam {string} path_foto path foto sesuai yang disimpan di record berita
*/

/**
* @api {get} /images?name=:nama_foto&path=:path_foto&size=:nilai 2.Mengatur Ukuran Foto
* @apiVersion 0.1.0
* @apiName getImageSize
* @apiGroup Images
* @apiPermission public
*
* @apiDescription digunakan untuk mengatur ukuran images/gambar dari foto yang ada pada direktori server<br><ul><li> Small = 100 Pixels</li><li> Medium = 300 Pixels</li><li> Original = Size Asli</li></ul>
*
* @apiExample Cara penggunaan:
* http://api.linksaya.com//images?name=siraman.jpg&path=./images/2017/11/07&size=small
*
* @apiParam {string} nama_foto nama foto sesuai di record berita
* @apiParam {string} path_foto path foto sesuai yang disimpan di record berita
* @apiParam {String} nilai ukuran foto yang dinginkan (small|medium|original)
*/