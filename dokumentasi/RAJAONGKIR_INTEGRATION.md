This comprehensive guide will walk you through everything you need to know to get started, including setup, authentication, and choosing the right integration method for your needs. Whether you're building a shipping calculator or any application that requires shipping data, this API has you covered.

Go to the RajaOngkir website and sign up for an account.
After registering, log in to your dashboard.
Navigate to the API Key section and generate your unique key. This key will be used to authenticate all your API requests.
RajaOngkir API V2 provides
two different approaches
for accessing shipping data. Choose the method that best fits your application's user experience:

Best for:
Traditional forms, dropdown menus, structured selections

How it works:

Get list of provinces
Select province → Get cities in that province
Select city → Get districts in that city
Select district → Get subdistricts in that district
(optional)
Use District IDs or Subdistrict IDs for cost calculation
Perfect for:
Applications where users prefer guided selection through dropdowns

Best for:
Modern search interfaces, autocomplete, quick lookups

How it works:

User types city name directly (e.g., "Jakarta", "Surabaya")
Get instant results with complete location details down to Subdistrict level
Use Subdistrict IDs for cost calculation
Perfect for:
Applications with search boxes or when users know their destination

Based on your chosen method, you'll use different endpoints:

Search Province Endpoint
: Get list of all provinces
Search City Endpoint
: Get cities by province ID
Search District Endpoint
: Get districts by city ID
Search Subdistrict Endpoint
: Get subdistricts by district ID
(optional)
Cost Calculation Endpoint
: Calculate shipping costs using
District IDs
or
Subdistrict IDs
Search Domestic Destination Endpoint
: Direct search by city name (returns Subdistrict IDs)
Search International Destination Endpoint
: Get international destinations
Cost Calculation Endpoint
: Calculate costs using
Subdistrict IDs
from search results
Waybills Endpoint
: Real-time package tracking
Stop at district level in the hierarchy
Use District ID for cost calculation
Less precise but faster process
Continue to subdistrict level in the hierarchy
Use Subdistrict ID for cost calculation
More precise location-based pricing
Only uses Subdistrict ID
for calculation
Search results automatically include detailed location data down to subdistrict level
More accurate pricing with fewer API calls
Choose the example based on your selected method:

# Get list of provinces first
curl --location 'https://rajaongkir.komerce.id/api/v1/destination/province' \
--header 'key: YOUR_API_KEY'
# Search for a city directly (gets Subdistrict IDs)
curl --location 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search=jakarta&limit=5&offset=0' \
--header 'key: YOUR_API_KEY'
Replace YOUR_API_KEY with the key you generated earlier.

Successful Response
: The API returns a 200 OK status with the requested data.
Error Response
: You'll get an error code (e.g., 400 Bad Request) and a description. Check the error codes for troubleshooting.
After getting location IDs from either method:

Use
District ID
for general calculation, OR
Use
Subdistrict ID
for more precise calculation
Use
Subdistrict ID
from search results
Waybill Tracking
: Get real-time tracking updates for your shipments
International Shipping
: Calculate costs for worldwide destinations
Multiple Courier Support
: Compare rates across different shipping providers
Secure Your API Key
: Never share your key publicly or include it in client-side code.
Use Environment Variables
: Store your API key securely in your application environment.
Rate Limiting
: Be aware of the rate limits based on your subscription tier. Avoid sending excessive requests in a short period.
Choose the Right Method
: Use Step-by-Step for structured forms, Direct Search for modern UX.
Select Appropriate Calculation Level
: Use District ID for speed, Subdistrict ID for accuracy.
Factor	Step-by-Step Method	Direct Search Method
User Experience
Guided selection
Instant search
UI Type
Dropdown forms
Search boxes
Development
Multiple API calls
Fewer API calls
Calculation Options
District ID or Subdistrict ID
Subdistrict ID only
Precision
Flexible (District/Subdistrict)
High (Subdistrict)
Best For
Traditional apps
Modern apps

This is the Live Environment where real shipping rate calculations Indonesia or International and also checking history AWB.

 success
Base URL Live Environment

https://rajaongkir.komerce.id/api/v1/
⚠️ Make sure to use the correct Base URL for environment. RajaOngkir API Service's won't work if you use wrong URL.

We provide information for each path of the Endpoint that can be used in the RajaOngkir API service as follows, please note that errors in the use of EndPoint paths can have an impact on errors in requests made by users:

Name	Path	Method	Description
Search Domestic Destination
destination/domestic-destination
GET
for Search origin or destination
id
in Indonesia Region
Search International Destination
destination/international-destination
GET
for Search destination
id
in World Wide
Calculate Domestic
calculate/domestic-cost
POST
for Search estimated shipping cost by origin and destination in Indonesia
Calculate International
calculate/international-cost
POST
for Search estimated shipping cost by origin and destination World Wide
Tracking AWB
track/waybill
POST
for Checking History AWB
To ensure a smooth and effective integration with the RajaOngkir API, we recommend the following best practices:

🔐 Always Authenticate Your Requests : Ensure your APIKEY is included in every request header. Unauthorized requests will be rejected.
📦 Validate Inputs Before Request : Double-check that required fields (like origin, destination, courier, weight) are correctly filled before calling the cost or tracking endpoints. Invalid inputs may cause errors or inaccurate results.
🧠 Cache Static Data : Static data such as province lists or courier names rarely changes. Cache them locally to avoid unnecessary API calls and improve performance.
⏱ Use Debouncing for Destination Search : When implementing live search for destinations, debounce user input to limit request frequency. This prevents flooding the API and improves user experience.
🔁 Handle API Errors Gracefully : Check for HTTP status codes and use fallback messages to guide users when something goes wrong.

To interact with the RajaOngkir API, you must authenticate your requests using an
APIKEY
. This key is a unique identifier issued to your account and is required in every request to verify your identity and grant access to the appropriate resources.

Without this key, the system will reject your request with a
401 Unauthorized
error.

Follow these steps to locate your API key:

Login
to your
Navigate to the
Integration
menu
Click on
Api Key
You'll find
Shipping Cost
APIKEY
Do not use API keys intended for other services (e.g. Shipping Delivery, etc.)
 info
⚠️
Important:
Treat your APIKEY like a password. Never share it or expose it publicly (e.g., in GitHub repos or front-end code).

Include your API key as a Middleware in the HEADER of each API request:

key: YOUR_API_KEY
curl --request GET \
  --url https://rajaongkir.komerce.id/api/v1
  --header 'key: YOUR_API_KEY'
This will return the list of available couriers for your checking cost and history AWB.

✅ Keep it secret: Never expose your API Key in frontend apps.
🔐 Rotate regularly: Periodically regenerate keys to enhance security.
🧪 Use sandbox for testing: Always use the test key before going live.
🔎 Log usage: Monitor API activity to prevent abuse or quota overages.
🔁 Refresh keys if compromised: Immediately revoke and generate a new one.

The
Courier Availability
section helps you understand which shipping couriers are supported by RajaOngkir and what types of logistics services they offer. This reference is essential for platform owners, integrators, and developers to design their shipping options effectively based on what’s available per courier — whether it's regular delivery, cargo, instant, or international shipping.

The following information can be used from each available courier to check the cost of domestic, international shipments and also track delivery receipts that are being made

 danger
Important Information Keep in mind that our system may only provide some information from the request you provide, it can all happen because of the absence of data that we find what you are looking for.

Courier	Code	Checking Domestics Cost	Checking International Cost	Checking AWB
JNE
jne
✅
✅
✅
SiCepat
sicepat
✅
❌
✅
IDExpress
ide
✅
❌
❌
SAP Express
sap
✅
❌
✅
Ninja
ninja
✅
❌
✅
J&T Express
jnt
✅
❌
✅
TIKI
tiki
✅
✅
✅
Wahana Express
wahana
✅
❌
✅
POS Indonesia
pos
✅
✅
✅
Sentral Cargo
sentral
✅
❌
❌
Lion Parcel
lion
✅
❌
✅
Royal Express Asia
rex
✅
❌
❌

Description:

The Step-by-Step Method endpoint in RajaOngkir refers to the API used to retrieve a hierarchical list of locations (provinces → cities → districts → subdistricts) in a structured, sequential manner. This data is essential for applications that calculate shipping rates, as it provides the required location identifiers needed to define an origin or destination when requesting shipping costs.

This endpoint serves as the foundation for any logistics or e-commerce platform that integrates with RajaOngkir, especially when users need to select where a package will be shipped from or delivered to through dropdown menus or form-based selections.

In short, this endpoint provides the geographic base data in a structured hierarchy (Province → City → District → Subdistrict) required to accurately calculate and display shipping options using the RajaOngkir service.

🔹
What the Step-by-Step Method Includes:

Province List
: Used to populate the first layer of location selection - provides all available provinces in Indonesia
City/Regency List
: Depends on the selected province; used to refine location selection to specific cities or regencies within that province
District List
: Offers more precise location targeting under a specific city or regency - provides district-level granularity
Subdistrict List
:
(Optional)
Provides the most detailed location level for maximum precision and accuracy in shipping calculations
Calculate Cost
: Uses the selected location data (either
District ID
or
Subdistrict ID
), along with weight and courier, to calculate the total shipping cost
🔹
Two Calculation Options:

Stop at district level in the hierarchy
Faster process with fewer API calls
Suitable for general shipping calculations
Less precise but adequate for most use cases
Continue to the most detailed subdistrict level
More precise location-based pricing
Recommended for accurate shipping cost calculations
Requires one additional API call to get subdistrict data
Use Cases:

Form-based applications with cascading dropdowns
Applications requiring structured location selection
When you need to display complete location hierarchy
Perfect for traditional web forms and mobile app forms
Ideal when users prefer guided selection process through multiple steps
Suitable for applications where location accuracy can be flexible (District vs Subdistrict level)
Great for admin panels and backend management systems


Search Province
https://rajaongkir.komerce.id/api/v1/destination/province

The Search Province Endpoint serves as the foundational step in the hierarchical location selection process for RajaOngkir API. This endpoint provides a complete list of all provinces in Indonesia, which is essential for applications that need to implement structured location selection through cascading dropdown menus or multi-step forms.

This endpoint is particularly valuable for e-commerce platforms, logistics applications, and shipping calculators that require users to select their location in a guided, step-by-step manner. By starting with province selection, applications can provide a familiar and intuitive user experience similar to traditional address forms.

Method:

GET

Base URL:

https://rajaongkir.komerce.id/api/v1/destination/province

Description:

Retrieves a comprehensive list of all Indonesian provinces with their corresponding unique identifiers. This data serves as the first level in the location hierarchy and is required to subsequently fetch cities and districts within each province.

This endpoint is the starting point for the Step-by-Step Method integration approach in RajaOngkir API V2.

PHP
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://rajaongkir.komerce.id/api/v1/destination/province', [
  'headers' => [
    'accept' => 'application/json',
  ],
]);

echo $response->getBody();


Response
200
{
  "meta": {
    "message": "Success Get Province",
    "code": 200,
    "status": "success"
  },
  "data": [
    {
      "id": 1,
      "name": "NUSA TENGGARA BARAT (NTB)"
    },
    {
      "id": 2,
      "name": "NUSA TENGGARA BARAT"
    },
    {
      "id": 3,
      "name": "MALUKU"
    },
    {
      "id": 4,
      "name": "KALIMANTAN SELATAN"
    },
    {
      "id": 5,
      "name": "KALIMANTAN TENGAH"
    },
    {
      "id": 6,
      "name": "JAWA BARAT"
    },
    {
      "id": 7,
      "name": "BENGKULU"
    },
    {
      "id": 8,
      "name": "KALIMANTAN TIMUR"
    },
    {
      "id": 9,
      "name": "KEPULAUAN RIAU"
    },
    {
      "id": 10,
      "name": "NANGGROE ACEH DARUSSALAM (NAD)"
    },
    {
      "id": 11,
      "name": "DKI JAKARTA"
    },
    {
      "id": 12,
      "name": "BANTEN"
    },
    {
      "id": 13,
      "name": "JAWA TENGAH"
    },
    {
      "id": 14,
      "name": "JAMBI"
    },
    {
      "id": 15,
      "name": "PAPUA"
    },
    {
      "id": 16,
      "name": "BALI"
    },
    {
      "id": 17,
      "name": "SUMATERA UTARA"
    },
    {
      "id": 18,
      "name": "GORONTALO"
    },
    {
      "id": 19,
      "name": "JAWA TIMUR"
    },
    {
      "id": 20,
      "name": "DI YOGYAKARTA"
    },
    {
      "id": 21,
      "name": "SULAWESI TENGGARA"
    },
    {
      "id": 22,
      "name": "NUSA TENGGARA TIMUR (NTT)"
    },
    {
      "id": 23,
      "name": "SULAWESI UTARA"
    },
    {
      "id": 24,
      "name": "SUMATERA BARAT"
    },
    {
      "id": 25,
      "name": "BANGKA BELITUNG"
    },
    {
      "id": 26,
      "name": "RIAU"
    },
    {
      "id": 27,
      "name": "SUMATERA SELATAN"
    },
    {
      "id": 28,
      "name": "SULAWESI TENGAH"
    },
    {
      "id": 29,
      "name": "KALIMANTAN BARAT"
    },
    {
      "id": 30,
      "name": "PAPUA BARAT"
    },
    {
      "id": 31,
      "name": "LAMPUNG"
    },
    {
      "id": 32,
      "name": "KALIMANTAN UTARA"
    },
    {
      "id": 33,
      "name": "MALUKU UTARA"
    },
    {
      "id": 34,
      "name": "SULAWESI SELATAN"
    },
    {
      "id": 35,
      "name": "SULAWESI BARAT"
    }
  ]
}

400
{
  "meta": {
    "message": "Invalid Api key, key not found",
    "code": 400,
    "status": "failed"
  },
  "data": null
}


Search City
https://rajaongkir.komerce.id/api/v1/destination/city/{province_id}

The Search City Endpoint represents the second crucial step in the hierarchical location selection process for RajaOngkir API. This endpoint retrieves all cities within a specific province, allowing applications to build comprehensive cascading location selection systems. By providing the province ID obtained from the Search Province endpoint, users can access detailed city information required for precise shipping calculations.

This endpoint is essential for applications that implement structured address selection, enabling users to narrow down their location choice progressively. It's particularly valuable for e-commerce platforms, logistics management systems, and shipping calculators that require accurate city-level geographic data for cost calculations and delivery planning.

Method:

GET

Base URL:

https://rajaongkir.komerce.id/api/v1/destination/city/{province_id}

Description:

Retrieves a comprehensive list of all cities within a specified Indonesian province using the province ID. This data serves as the second level in the location hierarchy and is required to subsequently fetch districts within each selected city.

This endpoint is the second step in the Step-by-Step Method integration approach, building upon the province selection to provide more granular location data for shipping calculations.

PHP
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://rajaongkir.komerce.id/api/v1/destination/city', [
  'headers' => [
    'accept' => 'application/json',
    'key' => 'YOUR_API_KEY',
  ],
]);

echo $response->getBody();

Response
200
{
  "meta": {
    "message": "Success Get District By City ID",
    "code": 200,
    "status": "success"
  },
  "data": [
    {
      "id": 1360,
      "name": "JAKARTA SELATAN",
      "zip_code": "0"
    },
    {
      "id": 1361,
      "name": "JAGAKARSA",
      "zip_code": "12630"
    },
    {
      "id": 1362,
      "name": "KEBAYORAN BARU",
      "zip_code": "12150"
    },
    {
      "id": 1363,
      "name": "KEBAYORAN LAMA",
      "zip_code": "12230"
    },
    {
      "id": 1364,
      "name": "MAMPANG PRAPATAN",
      "zip_code": "12730"
    },
    {
      "id": 1365,
      "name": "PANCORAN",
      "zip_code": "12770"
    },
    {
      "id": 1366,
      "name": "PASAR MINGGU",
      "zip_code": "12560"
    },
    {
      "id": 1367,
      "name": "PESANGGRAHAN",
      "zip_code": "12330"
    },
    {
      "id": 1368,
      "name": "SETIA BUDI",
      "zip_code": "12980"
    },
    {
      "id": 1369,
      "name": "TEBET",
      "zip_code": "12840"
    },
    {
      "id": 1370,
      "name": "CILANDAK",
      "zip_code": "12430"
    }
  ]
}

400
{
  "meta": {
    "message": "Invalid Api key, key not found",
    "code": 400,
    "status": "failed"
  },
  "data": null
}

Search District
https://rajaongkir.komerce.id/api/v1/destination/district/{city_id}

The
Search District Endpoint
represents the third vital step in the hierarchical location selection process for the RajaOngkir API. This endpoint retrieves all districts (kecamatan) within a specific city, enabling applications to offer a fully detailed and dynamic location selection experience down to the district level.

By supplying the city ID obtained from the
Search City
endpoint, users can access structured district data necessary for accurate shipping rate calculations, service availability checks, or address validation. This fine-grained geographic data is particularly beneficial for last-mile delivery logistics, dynamic form population, and e-commerce checkout flows.

This endpoint plays a critical role in systems that require complete administrative breakdowns, ensuring that end users can precisely define their shipping destinations with minimal error.

Method:

GET

Base URL:

https://rajaongkir.komerce.id/api/v1/destination/district/{city_id}

Description:

Retrieves a list of all districts within a specified Indonesian city using the city ID. This endpoint provides the third level in the location hierarchy and is essential for completing the destination input needed for shipping cost calculations and logistic planning.

This endpoint serves as the third step in the
Step-by-Step Method
integration model, following the city selection process and enabling deeper geographic targeting for shipping and service delivery.

PHP
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://rajaongkir.komerce.id/api/v1/destination/district/', [
  'headers' => [
    'accept' => 'application/json',
    'key' => 'YOUR_API_KEY',
  ],
]);

echo $response->getBody();

200
{
  "meta": {
    "message": "Success Get District By City ID",
    "code": 200,
    "status": "success"
  },
  "data": [
    {
      "id": 1360,
      "name": "JAKARTA SELATAN",
      "zip_code": "0"
    },
    {
      "id": 1361,
      "name": "JAGAKARSA",
      "zip_code": "12630"
    },
    {
      "id": 1362,
      "name": "KEBAYORAN BARU",
      "zip_code": "12150"
    },
    {
      "id": 1363,
      "name": "KEBAYORAN LAMA",
      "zip_code": "12230"
    },
    {
      "id": 1364,
      "name": "MAMPANG PRAPATAN",
      "zip_code": "12730"
    },
    {
      "id": 1365,
      "name": "PANCORAN",
      "zip_code": "12770"
    },
    {
      "id": 1366,
      "name": "PASAR MINGGU",
      "zip_code": "12560"
    },
    {
      "id": 1367,
      "name": "PESANGGRAHAN",
      "zip_code": "12330"
    },
    {
      "id": 1368,
      "name": "SETIA BUDI",
      "zip_code": "12980"
    },
    {
      "id": 1369,
      "name": "TEBET",
      "zip_code": "12840"
    },
    {
      "id": 1370,
      "name": "CILANDAK",
      "zip_code": "12430"
    }
  ]
}

400
{
  "meta": {
    "message": "Invalid Api key, key not found",
    "code": 400,
    "status": "failed"
  },
  "data": null
}

District Calculate Cost

https://rajaongkir.komerce.id/api/v1/calculate/district/domestic-cost

The
Calculate Domestic Cost by District Endpoint
is the final and most critical step in the hierarchical location selection process of the RajaOngkir API. This endpoint calculates the shipping cost between two districts within Indonesia based on selected courier services, package weight, and other parameters.

By leveraging the
origin
and
destination
district IDs obtained from previous steps, this endpoint returns real-time shipping rates from multiple couriers. It enables users to compare prices, estimate delivery fees, and make informed choices during checkout or logistics planning.

This endpoint is essential for e-commerce platforms, marketplace systems, delivery apps, and logistics tools that require automated, up-to-date cost calculations tailored to user-selected locations and shipping preferences.

Method:

POST

Base URL:

https://rajaongkir.komerce.id/api/v1/calculate/district/domestic-cost

Content-Type:

application/x-www-form-urlencoded

Description:

Calculates domestic shipping costs between two Indonesian districts using the selected couriers and package weight. The result includes shipping options, estimated delivery times, and total fees from multiple courier services.

This endpoint is the final step in the
Step-by-Step Method
integration flow, utilizing all previously gathered location data to provide accurate, courier-specific shipping cost estimations.

<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('POST', 'https://rajaongkir.komerce.id/api/v1/calculate/district/domestic-cost', [
  'headers' => [
    'accept' => 'application/json',
    'content-type' => 'application/json',
    'key' => 'YOUR_API_KEY',
  ],
]);

echo $response->getBody();

Response
200
{
  "meta": {
    "message": "Success Calculate Domestic Shipping cost",
    "code": 200,
    "status": "success"
  },
  "data": [
    {
      "name": "Lion Parcel",
      "code": "lion",
      "service": "JAGOPACK",
      "description": "Economy Service",
      "cost": 7000,
      "etd": "1-4 day"
    },
    {
      "name": "Lion Parcel",
      "code": "lion",
      "service": "REGPACK",
      "description": "Regular Service",
      "cost": 7500,
      "etd": "1-2 day"
    },
    {
      "name": "J&T Express",
      "code": "jnt",
      "service": "EZ",
      "description": "Reguler",
      "cost": 8000,
      "etd": ""
    },
    {
      "name": "Ninja Xpress",
      "code": "ninja",
      "service": "STANDARD",
      "description": "Standard Service",
      "cost": 8000,
      "etd": ""
    },
    {
      "name": "POS Indonesia (POS)",
      "code": "pos",
      "service": "Pos Reguler",
      "description": "240",
      "cost": 8000,
      "etd": "2 day"
    },
    {
      "name": "Satria Antaran Prima",
      "code": "sap",
      "service": "UDRREG",
      "description": "Reguler",
      "cost": 8000,
      "etd": "1-3 day"
    },
    {
      "name": "SiCepat Express",
      "code": "sicepat",
      "service": "REG",
      "description": "Reguler",
      "cost": 8000,
      "etd": "1-2 day"
    },
    {
      "name": "Royal Express Indonesia (REX)",
      "code": "rex",
      "service": "REG",
      "description": "Regular",
      "cost": 8500,
      "etd": "3-3 day"
    },
    {
      "name": "ID Express",
      "code": "ide",
      "service": "STD",
      "description": "Std",
      "cost": 9000,
      "etd": "0-0 day"
    },
    {
      "name": "Royal Express Indonesia (REX)",
      "code": "rex",
      "service": "EXP",
      "description": "Express",
      "cost": 9500,
      "etd": "2-2 day"
    },
    {
      "name": "Jalur Nugraha Ekakurir (JNE)",
      "code": "jne",
      "service": "CTC",
      "description": "JNE City Courier",
      "cost": 10000,
      "etd": "1 day"
    },
    {
      "name": "Lion Parcel",
      "code": "lion",
      "service": "BOSSPACK",
      "description": "Priority Service",
      "cost": 10000,
      "etd": "1-1 day"
    },
    {
      "name": "Lion Parcel",
      "code": "lion",
      "service": "SAMEDAY",
      "description": "Unknown Service",
      "cost": 10000,
      "etd": "0-1 day"
    },
    {
      "name": "Royal Express Indonesia (REX)",
      "code": "rex",
      "service": "REX-1",
      "description": "Rex-1",
      "cost": 13500,
      "etd": "1-1 day"
    },
    {
      "name": "SiCepat Express",
      "code": "sicepat",
      "service": "BEST",
      "description": "Besok Sampai Tujuan",
      "cost": 14000,
      "etd": "1 day"
    },
    {
      "name": "POS Indonesia (POS)",
      "code": "pos",
      "service": "Pos Nextday",
      "description": "447",
      "cost": 15000,
      "etd": "1 day"
    },
    {
      "name": "Satria Antaran Prima",
      "code": "sap",
      "service": "UDRONS",
      "description": "Nextday",
      "cost": 15000,
      "etd": "1-2 day"
    },
    {
      "name": "POS Indonesia (POS)",
      "code": "pos",
      "service": "PAKETPOS DANGEROUS GOODS",
      "description": "Pdg",
      "cost": 16500,
      "etd": "3 day"
    },
    {
      "name": "POS Indonesia (POS)",
      "code": "pos",
      "service": "PAKETPOS VALUABLE GOODS",
      "description": "Pvg",
      "cost": 16500,
      "etd": "3 day"
    },
    {
      "name": "Jalur Nugraha Ekakurir (JNE)",
      "code": "jne",
      "service": "CTCYES",
      "description": "JNE City Courier",
      "cost": 18000,
      "etd": "1 day"
    },
    {
      "name": "POS Indonesia (POS)",
      "code": "pos",
      "service": "Pos Sameday",
      "description": "2q9",
      "cost": 18000,
      "etd": "0 day"
    },
    {
      "name": "Satria Antaran Prima",
      "code": "sap",
      "service": "DRGREG",
      "description": "Cargo",
      "cost": 22500,
      "etd": "2-4 day"
    },
    {
      "name": "Jalur Nugraha Ekakurir (JNE)",
      "code": "jne",
      "service": "CTCSPS",
      "description": "JNE City Courier",
      "cost": 25000,
      "etd": "1 day"
    },
    {
      "name": "Lion Parcel",
      "code": "lion",
      "service": "BIGPACK",
      "description": "Big Package Service",
      "cost": 30000,
      "etd": "3-4 day"
    },
    {
      "name": "Royal Express Indonesia (REX)",
      "code": "rex",
      "service": "REX-10",
      "description": "Rex-10",
      "cost": 32500,
      "etd": "3-3 day"
    },
    {
      "name": "Satria Antaran Prima",
      "code": "sap",
      "service": "UDRSDS",
      "description": "Sds",
      "cost": 34000,
      "etd": "0-1 day"
    },
    {
      "name": "POS Indonesia (POS)",
      "code": "pos",
      "service": "POS KARGO",
      "description": "Pjb",
      "cost": 35000,
      "etd": "7-14 day"
    },
    {
      "name": "SiCepat Express",
      "code": "sicepat",
      "service": "GOKIL",
      "description": "Cargo Per Kg (Minimal 10kg)",
      "cost": 35000,
      "etd": "2-3 day"
    },
    {
      "name": "Jalur Nugraha Ekakurir (JNE)",
      "code": "jne",
      "service": "JTR",
      "description": "JNE Trucking",
      "cost": 40000,
      "etd": "3 day"
    },
    {
      "name": "Royal Express Indonesia (REX)",
      "code": "rex",
      "service": "REX-0",
      "description": "Rex-0",
      "cost": 75000,
      "etd": "0-0 day"
    },
    {
      "name": "Royal Express Indonesia (REX)",
      "code": "rex",
      "service": "M-100",
      "description": "Motor 100cc",
      "cost": 200000,
      "etd": "2-2 day"
    },
    {
      "name": "Jalur Nugraha Ekakurir (JNE)",
      "code": "jne",
      "service": "JTR<130",
      "description": "JNE Trucking",
      "cost": 300000,
      "etd": "3 day"
    },
    {
      "name": "Royal Express Indonesia (REX)",
      "code": "rex",
      "service": "M-150",
      "description": "Motor 150cc",
      "cost": 300000,
      "etd": "2-2 day"
    },
    {
      "name": "Lion Parcel",
      "code": "lion",
      "service": "OTOPACK150",
      "description": "Otomotive Shipping Service",
      "cost": 315500,
      "etd": "4-6 day"
    },
    {
      "name": "Lion Parcel",
      "code": "lion",
      "service": "OTOPACK250",
      "description": "Otomotive Shipping Service",
      "cost": 438500,
      "etd": "4-6 day"
    },
    {
      "name": "Jalur Nugraha Ekakurir (JNE)",
      "code": "jne",
      "service": "JTR>130",
      "description": "JNE Trucking",
      "cost": 450000,
      "etd": "3 day"
    },
    {
      "name": "Jalur Nugraha Ekakurir (JNE)",
      "code": "jne",
      "service": "JTR>200",
      "description": "JNE Trucking",
      "cost": 550000,
      "etd": "3 day"
    }
  ]
}

400
{
  "meta": {
    "message": "Invalid Api key, key not found",
    "code": 400,
    "status": "failed"
  },
  "data": null
}

Search Subdistrict
https://rajaongkir.komerce.id/api/v1/destination/sub-district/{district_id}

The
Search Subdistrict Endpoint
represents the third vital step in the hierarchical location selection process for the RajaOngkir API. This endpoint retrieves all districts (kecamatan) within a specific city, enabling applications to offer a fully detailed and dynamic location selection experience down to the district level.

By supplying the city ID obtained from the
Search District
endpoint, users can access structured district data necessary for accurate shipping rate calculations, service availability checks, or address validation. This fine-grained geographic data is particularly beneficial for last-mile delivery logistics, dynamic form population, and e-commerce checkout flows.

This endpoint plays a critical role in systems that require complete administrative breakdowns, ensuring that end users can precisely define their shipping destinations with minimal error.

Method:

GET

Base URL:

https://rajaongkir.komerce.id/api/v1/destination/sub-district/{district_id}

Description:

Retrieves a list of all subdistrict within a specified Indonesian district using the district ID. This endpoint provides the third level in the location hierarchy and is essential for completing the destination input needed for shipping cost calculations and logistic planning.

This endpoint serves as the third step in the
Step-by-Step Method
integration model, following the city selection process and enabling deeper geographic targeting for shipping and service delivery.

Request Body
PHP
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://rajaongkir.komerce.id/api/v1/destination/sub-district', [
  'headers' => [
    'accept' => 'application/json',
    'key' => 'YOUR_API_KEY',
  ],
]);

echo $response->getBody();

Response
200
{}{
    "meta": {
        "message": "Success Get Sub District By District ID",
        "code": 200,
        "status": "success"
    },
    "data": [
        {
            "id": 68513,
            "name": "BALERAKSA",
            "zip_code": "53355"
        },
        {
            "id": 68514,
            "name": "GRANTUNG",
            "zip_code": "53355"
        },
        {
            "id": 68515,
            "name": "KARANGSARI",
            "zip_code": "53355"
        },
        {
            "id": 68516,
            "name": "KRAMAT",
            "zip_code": "53355"
        },
        {
            "id": 68517,
            "name": "PEKIRINGAN",
            "zip_code": "53355"
        },
        {
            "id": 68518,
            "name": "PEPEDAN",
            "zip_code": "53355"
        },
        {
            "id": 68519,
            "name": "RAJAWANA",
            "zip_code": "53355"
        },
        {
            "id": 68520,
            "name": "SIRAU",
            "zip_code": "53355"
        },
        {
            "id": 68521,
            "name": "TAJUG",
            "zip_code": "53355"
        },
        {
            "id": 68522,
            "name": "TAMANSARI",
            "zip_code": "53355"
        },
        {
            "id": 68523,
            "name": "TUNJUNGMULI",
            "zip_code": "53355"
        }
    ]
}

400
{
  "meta": {
    "message": "Invalid Api key, key not found",
    "code": 400,
    "status": "failed"
  },
  "data": null
}

