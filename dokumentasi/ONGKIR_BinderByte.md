
GET
Kelurahan / Desa
https://api.binderbyte.com/wilayah/kelurahan?api_key=8e49f28e0f2f2cf56393c352613eec358e85fb7077ce6f7f453ebb826a7b1f6d&id_kecamatan=367201
PARAMS
api_key
8e49f28e0f2f2cf56393c352613eec358e85fb7077ce6f7f453ebb826a7b1f6d

id_kecamatan
367201

Example Request
Success
View More
curl
curl --location 'https://api.binderbyte.com/wilayah/kelurahan?api_key=8e49f28e0f2f2cf56393c352613eec358e85fb7077ce6f7f453ebb826a7b1f6d&id_kecamatan=367201'
200 OK
Example Response
Body
Headers (11)
View More
json
{
  "code": "200",
  "messages": "Successfully received data",
  "value": [
    {
      "id": "3672011001",
      "id_kecamatan": "367201",
      "name": "Cibeber"
    },
    {
      "id": "3672011002",
      "id_kecamatan": "367201",
      "name": "Kedaleman"
    },
    {
      "id": "3672011003",
      "id_kecamatan": "367201",
      "name": "Bulakan"
    },
    {
      "id": "3672011004",
      "id_kecamatan": "367201",
      "name": "Cikerai"
    },
    {
      "id": "3672011005",
      "id_kecamatan": "367201",
      "name": "Karang Asem"
    },
    {
      "id": "3672011006",
      "id_kecamatan": "367201",
      "name": "Kalitimbang"
    }
  ]
}
API CEK RESI
GET
JNT
https://api.binderbyte.com/v1/track?api_key=8e49f28e0f2f2cf56393c352613eec358e85fb7077ce6f7f453ebb826a7b1f6d&courier=jnt&awb=JP2255749628
PARAMS
api_key
8e49f28e0f2f2cf56393c352613eec358e85fb7077ce6f7f453ebb826a7b1f6d

courier
jnt

awb
JP2255749628

Example Request
Success
View More
curl
curl --location 'https://api.binderbyte.com/v1/track?api_key=f8000a7fa7be89bb3796d9a753d248c2d1c0ac04ac994b7cb860b31240a730d1&courier=jnt&awb=JP2255749628'
200 OK
Example Response
Body
Headers (12)
View More
json
{
  "status": 200,
  "message": "Successfully tracked package",
  "data": {
    "summary": {
      "awb": "JP6961181926",
      "courier": "J&T Express",
      "service": "",
      "status": "DELIVERED",
      "date": "2020-10-01 12:30:28",
      "desc": "",
      "amount": "",
      "weight": ""
    },
    "detail": {
      "origin": "",
      "destination": "",
      "shipper": "",
      "receiver": ""
    },
    "history": [
      {
        "date": "2020-10-01 12:30:28",
        "desc": "TERKIRIM: DROP POINT [SUNGAI PENUH] (DITERIMA OLEH: MUHAMMAD LUTHFI ALHA)",
        "location": "SUNGAI PENUH"
      },
      {
        "date": "2020-10-01 09:04:16",
        "desc": "SEDANG DIANTAR: DROP POINT [SUNGAI PENUH] (CONTACT: ROMI EKA PUTRA +6282281231686)",
        "location": "SUNGAI PENUH"
      },
      {
        "date": "2020-10-01 07:06:25",
        "desc": "TELAH TIBA: DROP POINT [SUNGAI PENUH] DARI [DJB_GATEWAY]",
        "location": "SUNGAI PENUH"
      },
      {
        "date": "2020-10-01 01:35:27",
        "desc": "TELAH TIBA: TRANSIT CENTER [JAMBI] DARI [JKT_GATEWAY]",
        "location": "JAMBI"
      },
      {
        "date": "2020-09-30 17:22:48",
        "desc": "TELAH BERANGKAT: PUSAT TRANSIT [JAMBI] MENUJU [SUNGAI PENUH]",
        "location": "JAMBI"
      },
      {
        "date": "2020-09-29 09:54:45",
        "desc": "TELAH BERANGKAT: PUSAT TRANSIT [JAKARTA] MENUJU [JAMBI]",
        "location": "JAKARTA"
      },
      {
        "date": "2020-09-29 05:57:01",
        "desc": "TELAH TIBA: TRANSIT CENTER [JAKARTA] DARI [ANCOL]",
        "location": "JAKARTA"
      },
      {
        "date": "2020-09-29 01:22:34",
        "desc": "TELAH BERANGKAT: PUSAT TRANSIT [JAKARTA] MENUJU [JAKARTA]",
        "location": "JAKARTA"
      },
      {
        "date": "2020-09-28 22:06:44",
        "desc": "TELAH DIAMBIL: DROP POINT [JAKARTA] (DIPROSES OLEH: SANTOSO1)",
        "location": "JAKARTA"
      }
    ]
  }
}
GET
SiCepat
https://api.binderbyte.com/v1/track?api_key=8e49f28e0f2f2cf56393c352613eec358e85fb7077ce6f7f453ebb826a7b1f6d&courier=sicepat&awb=005244878857
PARAMS
api_key
8e49f28e0f2f2cf56393c352613eec358e85fb7077ce6f7f453ebb826a7b1f6d

courier
sicepat

awb
005244878857

Example Request
Success
View More
curl
curl --location 'https://api.binderbyte.com/v1/track?api_key=f8000a7fa7be89bb3796d9a753d248c2d1c0ac04ac994b7cb860b31240a730d1&courier=sicepat&awb=005229717534'
200 OK
Example Response
Body
Headers (12)
View More
json
{
  "status": 200,
  "message": "Successfully tracked package",
  "data": {
    "summary": {
      "awb": "000779194122",
      "courier": "SiCepat",
      "service": "REG",
      "status": "HILANG",
      "date": "2020-09-21 22:53",
      "desc": "",
      "amount": "",
      "weight": 1
    },
    "detail": {
      "origin": "DKI Jakarta",
      "destination": "Losari, Cirebon",
      "shipper": "Baseus Official Shop",
      "receiver": "Dwi Cahya Agustina"
    },
    "history": [
      {
        "date": "2020-09-29 17:15:00",
        "desc": "PAKET DI KEMBALIKAN DI CIREBON [CIREBON HUB] - (LOST) HILANG",
        "location": ""
      },
      {
        "date": "2020-09-22 08:42:00",
        "desc": "PAKET KELUAR DARI DKI JAKARTA [LINE HAUL DARAT JAKARTA 1]",
        "location": ""
      },
      {
        "date": "2020-09-22 07:35:00",
        "desc": "PAKET TELAH DI TERIMA DI DKI JAKARTA [LINE HAUL DARAT JAKARTA 1]",
        "location": ""
      },
      {
        "date": "2020-09-22 01:59:00",
        "desc": "PAKET KELUAR DARI DKI JAKARTA [JAKUT KAMAL MUARA]",
        "location": ""
      },
      {
        "date": "2020-09-21 22:53:00",
        "desc": "PAKET TELAH DI INPUT (MANIFESTED) DI JAKARTA UTARA [JAKUT KAMAL MUARA]",
        "location": ""
      },
      {
        "date": "2020-09-21 22:53:00",
        "desc": "PAKET TELAH DI PICK UP OLEH [SIGESIT - ]",
        "location": ""
      },
      {
        "date": "2020-09-21 09:04:00",
        "desc": "TERIMA PERMINTAAN PICK UP DARI [SHOPEE]",
        "location": ""
      }
    ]
  }
}
GET
Tiki
https://api.binderbyte.com/v1/track?api_key=f8000a7fa7be89bb3796d9a753d248c2d1c0ac04ac994b7cb860b31240a730d1&courier=tiki&awb=030205696069
PARAMS
api_key
f8000a7fa7be89bb3796d9a753d248c2d1c0ac04ac994b7cb860b31240a730d1

courier
tiki

awb
030205696069

Example Request
Success
View More
curl
curl --location 'https://api.binderbyte.com/v1/track?api_key=f8000a7fa7be89bb3796d9a753d248c2d1c0ac04ac994b7cb860b31240a730d1&courier=tiki&awb=030205696069'
200 OK
Example Response
Body
Headers (12)
View More
json
{
  "status": 200,
  "message": "Successfully tracked package",
  "data": {
    "summary": {
      "awb": "030205696069",
      "courier": "Tiki",
      "service": "REG",
      "status": "DELIVERED",
      "date": "2020-07-06 09:48:17",
      "desc": "",
      "amount": "22000",
      "weight": "1"
    },
    "detail": {
      "origin": "MALANG",
      "destination": "CIANJUR - JAWA BARAT",
      "shipper": "SAKILA OLSHOP",
      "receiver": "SITI BADRIAH"
    },
    "history": [
      {
        "date": "2020-07-06 09:48:17",
        "desc": "SUCCESS / [M] RECEIVED BY: SITI",
        "location": ""
      },
      {
        "date": "2020-07-06 08:26:28",
        "desc": "WITH DELIVERY COURIER",
        "location": ""
      },
      {
        "date": "2020-07-06 08:16:50",
        "desc": "ARRIVED AT TIKI CIANJUR",
        "location": ""
      },
      {
        "date": "2020-07-06 02:39:53",
        "desc": "DEPARTED TO CIANJUR",
        "location": ""
      },
      {
        "date": "2020-07-06 02:25:33",
        "desc": "TRANSIT AT JAKARTA",
        "location": ""
      },
      {
        "date": "2020-07-04 23:39:20",
        "desc": "DEPARTED TO JAKARTA",
        "location": ""
      },
      {
        "date": "2020-07-04 19:02:23",
        "desc": "TRANSIT AT SURABAYA",
        "location": ""
      },
      {
        "date": "2020-07-04 14:08:14",
        "desc": "DEPARTED TO SURABAYA",
        "location": ""
      },
      {
        "date": "2020-07-04 12:11:54",
        "desc": "ARRIVE AT TIKI AT MALANG",
        "location": ""
      },
      {
        "date": "2020-07-04 10:21:03",
        "desc": "SHIPMENT DATA ENTRY AT MALANG",
        "location": ""
      }
    ]
  }
}
GET
Lion
https://api.binderbyte.com/v1/track?api_key=8e49f28e0f2f2cf56393c352613eec358e85fb7077ce6f7f453ebb826a7b1f6d&courier=lion&awb=11LP1715594502994
PARAMS
api_key
8e49f28e0f2f2cf56393c352613eec358e85fb7077ce6f7f453ebb826a7b1f6d

courier
lion

awb
11LP1715594502994

Example Request
Success
View More
curl
curl --location 'https://api.binderbyte.com/v1/track?api_key=f8000a7fa7be89bb3796d9a753d248c2d1c0ac04ac994b7cb860b31240a730d1&courier=lion&awb=11205949687'
200 OK
Example Response
Body
Headers (12)
View More
json
{
  "status": 200,
  "message": "Successfully tracked package",
  "data": {
    "summary": {
      "awb": "11205949687",
      "courier": "Lion Parcel",
      "service": "",
      "status": "DELIVERED",
      "date": "2020-10-01 08:37:28",
      "desc": "",
      "amount": "",
      "weight": ""
    },
    "detail": {
      "origin": "",
      "destination": "",
      "shipper": "",
      "receiver": ""
    },
    "history": [
      {
        "date": "2020-10-01 08:37:28",
        "desc": "PAKET TELAH DITERIMA OLEH (RECEIPT BY PENERIMA : HERMAN), PALEMBANG",
        "location": "PLM"
      },
      {
        "date": "2020-10-01 07:59:21",
        "desc": "PAKET AKAN DIKIRIMKAN OLEH (PT POS. ), PALEMBANG",
        "location": "PLM"
      },
      {
        "date": "2020-09-23 22:25:21",
        "desc": "PAKET TELAH SAMPAI DI (KONSOLIDATOR TUJUAN), PALEMBANG",
        "location": "PLM"
      },
      {
        "date": "2020-09-22 10:40:43",
        "desc": "PAKET TELAH SAMPAI DI (KONSOLIDATOR ASAL), JOGJAKARTA",
        "location": "JOG"
      },
      {
        "date": "2020-09-22 06:47:55",
        "desc": "PAKET TELAH DI PROSES OLEH (LION PARCEL AFANDI 3), JOGJAKARTA",
        "location": "JOG"
      }
    ]
  }
}
GET
PCP Express
https://api.binderbyte.com/v1/track?api_key=8e49f28e0f2f2cf56393c352613eec358e85fb7077ce6f7f453ebb826a7b1f6d&courier=pcp&awb=000131129V
PARAMS
api_key
8e49f28e0f2f2cf56393c352613eec358e85fb7077ce6f7f453ebb826a7b1f6d

courier
pcp

awb
000131129V

Example Request
Success
View More
curl
curl --location 'https://api.binderbyte.com/v1/track?api_key=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx&courier=pcp&awb=000131129V'
200 OK
Example Response
Body
Headers (17)
View More
json
{
  "status": 200,
  "message": "Successfully tracked package",
  "data": {
    "summary": {
      "awb": "000131129V",
      "courier": "PCP Express",
      "service": "TITIPAN REGULER EXPRESS - (TREX)",
      "status": "DELIVERED",
      "date": "2019-11-28 11:43:43",
      "desc": "",
      "amount": "",
      "weight": ""
    },
    "detail": {
      "origin": "SURABAYA",
      "destination": "PRAMBON, NGANJUK",
      "shipper": "PT. ORINDO ALAM AYU",
      "receiver": "ELLIFINA MAHZUZANA"
    },
    "history": [
      {
        "date": "2019-11-28 11:43:43",
        "desc": "SHIPMENT STATUS DELIVERED DITERIMA OLEH-ELIFINA-26-11-2019-15:15",
        "location": ""
      },
      {
        "date": "2019-11-22 00:06:07",
        "desc": "DELIVERY PROCESS",
        "location": ""
      },
      {
        "date": "2019-11-21 21:41:01",
        "desc": "ARRIVE AT OPERATION AOSUB191121075",
        "location": ""
      },
      {
        "date": "2019-11-21 14:23:08",
        "desc": "SHIPMENT ENTRY",
        "location": ""
      }
    ]
  }
}
GET
Lazada Express
https://api.binderbyte.com/v1/track?api_key=cb978c0a4a9574dafcxxxxxxxxxxxxxxxxxxxx&courier=lex&awb=LXAD-3194878510
PARAMS
api_key
cb978c0a4a9574dafcxxxxxxxxxxxxxxxxxxxx

courier
lex

awb
LXAD-3194878510

Example Request
Success
View More
curl
curl --location 'https://api.binderbyte.com/v1/track?api_key=cb978c0a4a9574dafc1exxxxxxxxxxxxxxxxxxxxxxx&courier=lex&awb=LXAD-3194878510'
200 OK
Example Response
Body
Headers (17)
View More
json
{
  "status": 200,
  "message": "Successfully tracked package",
  "data": {
    "summary": {
      "awb": "799955509662",
      "courier": "RPX Holding (RPX)",
      "service": "",
      "status": "DELIVERED",
      "date": "",
      "desc": "",
      "amount": "",
      "weight": ""
    },
    "detail": {
      "origin": "",
      "destination": "Kota Bogor",
      "shipper": "",
      "receiver": "MEGA SILVIA"
    },
    "history": [
      {
        "date": "2021-02-24 15:56:00",
        "desc": "SHIPMENT RECEIVED BY CONSIGNEE",
        "location": ""
      },
      {
        "date": "2021-02-24 09:25:00",
        "desc": "SHIPMENT ON PROCESS OF DELIVERY",
        "location": ""
      },
      {
        "date": "2021-02-24 09:24:00",
        "desc": "SHIPMENT ON PROCESS OF DELIVERY",
        "location": ""
      },
      {
        "date": "2021-02-24 09:13:00",
        "desc": "SHIPMENT ON PROCESS OF DELIVERY",
        "location": ""
      },
      {
        "date": "2021-02-24 07:50:00",
        "desc": "SHIPMENT ARRIVED AT STATION DESTINATION",
        "location": ""
      },
      {
        "date": "2021-02-23 23:13:00",
        "desc": "SHIPMENT ARRIVED AT TRANSIT AREAS",
        "location": ""
      },
      {
        "date": "2021-02-23 22:45:00",
        "desc": "DEPARTED FROM RPX ORIGIN STATION",
        "location": ""
      },
      {
        "date": "2021-02-23 22:06:00",
        "desc": "PICKED UP",
        "location": ""
      },
      {
        "date": "2021-02-23 21:08:00",
        "desc": "MANIFESTING PACKAGE",
        "location": ""
      },
      {
        "date": "2021-02-15 17:19:00",
        "desc": "PICKED UP",
        "location": ""
      }
    ]
  }
}
GET
Indah Cargo
https://api.binderbyte.com/v1/track?api_key=cb978c0a4a9574dafcxxxxxxxxxxxxxxxxxxxx&courier=indah_cargo&awb=PNG1CS18759907
PARAMS
api_key
cb978c0a4a9574dafcxxxxxxxxxxxxxxxxxxxx

courier
indah_cargo

awb
PNG1CS18759907

Example Request
Success
View More
curl
curl --location 'https://api.binderbyte.com/v1/track?api_key=cb978c0a4a9574dafc1exxxxxxxxxxxxxxxxxxxxxxx&courier=indah_cargo&awb=PNG1CS18759907'
200 OK
Example Response
Body
Headers (17)
View More
json
{
  "status": 200,
  "message": "Successfully tracked package",
  "data": {
    "summary": {
      "awb": "799955509662",
      "courier": "RPX Holding (RPX)",
      "service": "",
      "status": "DELIVERED",
      "date": "",
      "desc": "",
      "amount": "",
      "weight": ""
    },
    "detail": {
      "origin": "",
      "destination": "Kota Bogor",
      "shipper": "",
      "receiver": "MEGA SILVIA"
    },
    "history": [
      {
        "date": "2021-02-24 15:56:00",
        "desc": "SHIPMENT RECEIVED BY CONSIGNEE",
        "location": ""
      },
      {
        "date": "2021-02-24 09:25:00",
        "desc": "SHIPMENT ON PROCESS OF DELIVERY",
        "location": ""
      },
      {
        "date": "2021-02-24 09:24:00",
        "desc": "SHIPMENT ON PROCESS OF DELIVERY",
        "location": ""
      },
      {
        "date": "2021-02-24 09:13:00",
        "desc": "SHIPMENT ON PROCESS OF DELIVERY",
        "location": ""
      },
      {
        "date": "2021-02-24 07:50:00",
        "desc": "SHIPMENT ARRIVED AT STATION DESTINATION",
        "location": ""
      },
      {
        "date": "2021-02-23 23:13:00",
        "desc": "SHIPMENT ARRIVED AT TRANSIT AREAS",
        "location": ""
      },
      {
        "date": "2021-02-23 22:45:00",
        "desc": "DEPARTED FROM RPX ORIGIN STATION",
        "location": ""
      },
      {
        "date": "2021-02-23 22:06:00",
        "desc": "PICKED UP",
        "location": ""
      },
      {
        "date": "2021-02-23 21:08:00",
        "desc": "MANIFESTING PACKAGE",
        "location": ""
      },
      {
        "date": "2021-02-15 17:19:00",
        "desc": "PICKED UP",
        "location": ""
      }
    ]
  }
}
GET
Kurir Rekomendasi
https://api.binderbyte.com/v1/track?api_key=cb978c0a4a9574dafcxxxxxxxxxxxxxxxxxxxx&courier=kurir_tokopedia&awb=TKP01-C0KQJMC
PARAMS
api_key
cb978c0a4a9574dafcxxxxxxxxxxxxxxxxxxxx

courier
kurir_tokopedia

awb
TKP01-C0KQJMC

Example Request
Success
View More
curl
curl --location 'https://api.binderbyte.com/v1/track?api_key=cb978c0a4a9574dafc1exxxxxxxxxxxxxxxxxxxxxxx&courier=kurir_tokopedia&awb=TKP01-C0KQJMC'
200 OK
Example Response
Body
Headers (17)
View More
json
{
  "status": 200,
  "message": "Successfully tracked package",
  "data": {
    "summary": {
      "awb": "TKP01-C0KQJMC",
      "courier": "Kurir Rekomendasi",
      "service": "Regular",
      "status": "DELIVERED",
      "date": "",
      "desc": "",
      "amount": "",
      "weight": ""
    },
    "detail": {
      "origin": "Kalideres, Kota Administrasi Jakarta Barat, DKI Jakarta",
      "destination": "Pondok Aren, Kota Tangerang Selatan, Banten",
      "shipper": "AUTONET COVER",
      "receiver": "Agung Prasetyo"
    },
    "history": [
      {
        "date": "17 Mei 12:20 WIB",
        "desc": "Yay, pesanan sudah sampai dan diterima oleh Agung Prasetyo; Penerima asli [KURIR: ANTERAJA]",
        "location": "Tangerang"
      },
      {
        "date": "17 Mei 10:18 WIB",
        "desc": "Kurir sudah ditugaskan dan pesanan akan segera diantar ke pembeli. [KURIR: ANTERAJA]",
        "location": "Tangerang"
      },
      {
        "date": "17 Mei 08:21 WIB",
        "desc": "Pesanan sampai di sorting center Tangerang (Hub Tangerang). [KURIR: ANTERAJA]",
        "location": "Tangerang"
      },
      {
        "date": "17 Mei 05:30 WIB",
        "desc": "Pesanan keluar dari sorting center Tangerang (Hub Tangerang). [KURIR: ANTERAJA]",
        "location": "Tangerang"
      },
      {
        "date": "15 Mei 17:41 WIB",
        "desc": "Pesanan sudah di-pickup oleh kurir. [KURIR: ANTERAJA]",
        "location": "Jakarta Timur"
      },
      {
        "date": "15 Mei 09:01 WIB",
        "desc": "Kurir dalam perjalanan untuk mengambil pesanan dari penjual. [KURIR: ANTERAJA]",
        "location": "Jakarta Utara"
      },
      {
        "date": "13 Mei 15:55 WIB",
        "desc": "Kurir mitra logistik kami akan pick-up pesanan pada Minggu 14 Mei 2023 sekitar jam 16:00 WIB. [KURIR: ANTERAJA]",
        "location": ""
      },
      {
        "date": "13 Mei 15:55 WIB",
        "desc": "Penjual telah melakukan request pick-up. Kurir akan pick-up pesanan pada Minggu 14 Mei 2023 pukul 10:00 - 16:00. [KURIR: ANTERAJA]",
        "location": ""
      }
    ]
  }
}
GET
List Courier
https://api.binderbyte.com/v1/list_courier?api_key=cb978c0a4a9574dafcxxxxxxxxxxxxxxxxxxxx
PARAMS
api_key
cb978c0a4a9574dafcxxxxxxxxxxxxxxxxxxxx

Example Request
Success
View More
curl
curl --location 'https://api.binderbyte.com/v1/track?api_key=cb978c0a4a9574dafc1exxxxxxxxxxxxxxxxxxxxxxx'
200 OK
Example Response
Body
Headers (17)
View More
json
[
  {
    "code": "jne",
    "description": "JNE Express"
  },
  {
    "code": "pos",
    "description": "POS Indonesia"
  },
  {
    "code": "jnt",
    "description": "J&T Express"
  },
  {
    "code": "jnt_cargo",
    "description": "J&T Cargo"
  },
  {
    "code": "sicepat",
    "description": "SiCepat"
  },
  {
    "code": "tiki",
    "description": "TIKI"
  },
  {
    "code": "anteraja",
    "description": "AnterAja"
  },
  {
    "code": "wahana",
    "description": "Wahana"
  },
  {
    "code": "ninja",
    "description": "Ninja Express"
  },
  {
    "code": "lion",
    "description": "Lion Parcel"
  },
  {
    "code": "pcp",
    "description": "PCP Express"
  },
  {
    "code": "jet",
    "description": "JET Express"
  },
  {
    "code": "rex",
    "description": "REX Express"
  },
  {
    "code": "first",
    "description": "First Logistics"
  },
  {
    "code": "ide",
    "description": "ID Express"
  },
  {
    "code": "spx",
    "description": "Shopee Express"
  },
  {
    "code": "kgx",
    "description": "KGXpress"
  },
  {
    "code": "sap",
    "description": "SAP Express"
  },
  {
    "code": "rpx",
    "description": "RPX"
  },
  {
    "code": "lex",
    "description": "Lazada Express"
  },
  {
    "code": "indah_cargo",
    "description": "Indah Cargo"
  },
  {
    "code": "dakota",
    "description": "Dakota Cargo"
  },
  {
    "code": "kurir_tokopedia",
    "description": "Kurir Rekomendasi"
  }
]
GET
Check Quota/HIT
https://api.binderbyte.com/v1/checkQuota?api_key=cb978c0a4a9574dafcxxxxxxxxxxxxxxxxxxxx
PARAMS
api_key
cb978c0a4a9574dafcxxxxxxxxxxxxxxxxxxxx

Example Request
Success
View More
curl
curl --location 'https://api.binderbyte.com/v1/checkQuota?api_key=cb978c0a4a9574dafc1exxxxxxxxxxxxxxxxxxxxxxx'
200 OK
Example Response
Body
Headers (17)
json
[
{
    status: 200,
    product: "cek-resi",
    count: "31",
    limit: "2000",
    price: "0",
    balance: "0"
}
]