<?php

namespace App\Helpers\Admin\Seo;

use App\Helpers\AdminHelper;
use App\Http\Controllers\WebMainController;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class SchemaTools {

    public $StopeCash;
    public $setBr;
    public $lang;
    public $langalternate;


    public function __construct(
        $setBr = true,
        $StopeCash = 0,


    ) {
        $this->StopeCash = $StopeCash;
        $this->setBr = $setBr;
        $this->lang = thisCurrentLocale();
        if ($this->lang == 'ar') {
            $this->langalternate = 'en';
            $this->lastamenities = 'تقسيط';
        } else {
            $this->langalternate = 'ar';
            $this->lastamenities = 'installment';
        }

        $this->WebConfig = WebMainController::getWebConfig($this->StopeCash);
        $this->DefPhotoList = WebMainController::getDefPhotoList($this->StopeCash);


//        $this->amenities =  WebMainController::CashAmenityList($this->StopeCash);
//        dd($this->WebConfig->def_url);
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function Businesses() {

        $publisher_logo = getDefPhotoPath($this->DefPhotoList, 'logo', 'photo');

        $line = "";
        $line .= '<script type="application/ld+json">{' . self::PHP_MY_EOL();
        $line .= '"@context": "https://schema.org",' . self::PHP_MY_EOL();
        $line .= '"@type": "' . $this->WebConfig->schema_type . '",' . self::PHP_MY_EOL();
        $line .= '"@id": "' . $this->WebConfig->def_url . '",' . self::PHP_MY_EOL();

        if (count(config('app.web_lang'))) {
            $line .= '"name" : "' . $this->WebConfig->translate($this->lang)->name . '",' . self::PHP_MY_EOL();
            $line .= '"alternateName": "' . $this->WebConfig->translate($this->langalternate)->name . '",' . self::PHP_MY_EOL();
        } else {
            $line .= '"name" : "' . $this->WebConfig->translate($this->lang)->name . '",' . self::PHP_MY_EOL();
        }

        $line .= '"logo": "' . $publisher_logo . '",' . self::PHP_MY_EOL();
        $line .= '"image": "' . $publisher_logo . '",' . self::PHP_MY_EOL();
        $line .= '"url": "' . $this->WebConfig->def_url . '",' . self::PHP_MY_EOL();
        $line .= '"telephone": "' . $this->WebConfig->phone_call . '",' . self::PHP_MY_EOL();
        $line .= '"address": {' . self::PHP_MY_EOL();
        $line .= '"@type": "PostalAddress",' . self::PHP_MY_EOL();
        $line .= '"streetAddress": "' . $this->WebConfig->translate($this->lang)->schema_address . '",' . self::PHP_MY_EOL();
        $line .= '"addressLocality": "' . $this->WebConfig->translate($this->lang)->schema_city . '",' . self::PHP_MY_EOL();
        $line .= '"postalCode": "' . $this->WebConfig->schema_postal_code . '",' . self::PHP_MY_EOL();
        $line .= '"addressCountry": "' . $this->WebConfig->schema_country . '"' . self::PHP_MY_EOL();
//        $line .= '"addressRegion": "' . __('web/schema.b_address_region') . '"' . self::PHP_MY_EOL();
        $line .= '},' . self::PHP_MY_EOL();

        if ($this->WebConfig->schema_lat and $this->WebConfig->schema_long) {
            $line .= '"geo": {"@type": "GeoCoordinates","latitude": "' . $this->WebConfig->schema_lat . '","longitude": "' . $this->WebConfig->schema_long . '"},' . self::PHP_MY_EOL();
        }

        $line .= '"openingHoursSpecification": [' . self::PHP_MY_EOL();
        $line .= '{"@type": "OpeningHoursSpecification","dayOfWeek": "Monday","opens": "10:00","closes": "18:00"},' . self::PHP_MY_EOL();
        $line .= '{"@type": "OpeningHoursSpecification","dayOfWeek": "Tuesday","opens": "10:00","closes": "18:00"},' . self::PHP_MY_EOL();
        $line .= '{"@type": "OpeningHoursSpecification","dayOfWeek": "Wednesday","opens": "10:00","closes": "18:00"},' . self::PHP_MY_EOL();
        $line .= '{"@type": "OpeningHoursSpecification","dayOfWeek": "Thursday","opens": "10:00","closes": "18:00"},' . self::PHP_MY_EOL();
        $line .= '{"@type": "OpeningHoursSpecification","dayOfWeek": "Friday","opens": "10:00","closes": "18:00"},' . self::PHP_MY_EOL();
        $line .= '{"@type": "OpeningHoursSpecification","dayOfWeek": "Saturday","opens": "10:00","closes": "18:00"},' . self::PHP_MY_EOL();
        $line .= '{"@type": "OpeningHoursSpecification","dayOfWeek": "Sunday","opens": "10:00","closes": "18:00"}' . self::PHP_MY_EOL();
        $line .= '],' . self::PHP_MY_EOL();
        $line .= '"priceRange": "1000000",' . self::PHP_MY_EOL();
        $line .= '"sameAs": [' . self::PHP_MY_EOL();

        if ($this->WebConfig->facebook) {
            $line .= '"' . $this->WebConfig->facebook . '",' . self::PHP_MY_EOL();
        }
        if ($this->WebConfig->youtube) {
            $line .= '"' . $this->WebConfig->youtube . '",' . self::PHP_MY_EOL();
        }
        if ($this->WebConfig->twitter) {
            $line .= '"' . $this->WebConfig->twitter . '",' . self::PHP_MY_EOL();
        }
        if ($this->WebConfig->instagram) {
            $line .= '"' . $this->WebConfig->instagram . '",' . self::PHP_MY_EOL();
        }
        $line .= '"' . $this->WebConfig->def_url . '"' . self::PHP_MY_EOL();
        $line .= ']' . self::PHP_MY_EOL();
        $line .= '}</script>' . self::PHP_MY_EOL();

        return $line;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function Article($row, $route) {

        $url = urldecode(route($route, $row->slug));
        $Photo = getPhotoPath($row->photo, 'blog', "photo");
        $publisher_logo = getDefPhotoPath($this->DefPhotoList, 'logo', 'photo');

        $line = self::PHP_MY_EOL();
        $line .= '<script type="application/ld+json">' . self::PHP_MY_EOL();
        $line .= '{' . self::PHP_MY_EOL();
        $line .= '"@context": "https://schema.org",' . self::PHP_MY_EOL();
        $line .= '"@type": "NewsArticle",' . self::PHP_MY_EOL();
        $line .= '"url": "' . $url . '",' . self::PHP_MY_EOL();

        $line .= '"author": {' . self::PHP_MY_EOL();
        $line .= '"@type": "Website",' . self::PHP_MY_EOL();
        $line .= '"name": "' . $this->WebConfig->translate($this->lang)->name . '",' . self::PHP_MY_EOL();
        $line .= '"url": "' . $Photo . '"' . self::PHP_MY_EOL();
        $line .= '},' . self::PHP_MY_EOL();

        $line .= '"publisher":{' . self::PHP_MY_EOL();
        $line .= '"@type":"Organization",' . self::PHP_MY_EOL();
        $line .= '"name":"' . $this->WebConfig->translate($this->lang)->name . '",' . self::PHP_MY_EOL();
        $line .= '"logo":"' . $publisher_logo . '"' . self::PHP_MY_EOL();
        $line .= ' },' . self::PHP_MY_EOL();

        $line .= '"headline": "' . self::Clean($row->translate($this->lang)->name) . '",' . self::PHP_MY_EOL();
        $line .= '"mainEntityOfPage": "' . $url . '",' . self::PHP_MY_EOL();
        $line .= '"articleBody": "' . self::Clean($row->translate($this->lang)->g_des) . '",' . self::PHP_MY_EOL();
        $line .= '"image": "' . $Photo . '",' . self::PHP_MY_EOL();
        $line .= '"datePublished": "' . date(DATE_ATOM, strtotime($row->published_at)) . '",' . self::PHP_MY_EOL();
        $line .= '"dateModified": "' . date(DATE_ATOM, strtotime($row->updated_at)) . '"' . self::PHP_MY_EOL();

        $line .= '}' . self::PHP_MY_EOL();
        $line .= '</script>' . self::PHP_MY_EOL();

        return $line;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function Product($row, $route) {
        $url = urldecode(route($route, $row->slug));

        $Photo = getPhotoPath($row->photo, 'blog', "photo");
        $publisher_logo = getDefPhotoPath($this->DefPhotoList, 'logo', 'photo');

        $line = self::PHP_MY_EOL();

        $line .= '<script type="application/ld+json">{' . self::PHP_MY_EOL();
        $line .= '"@context": "https://schema.org/",' . self::PHP_MY_EOL();
        $line .= '"@type": "Product",' . self::PHP_MY_EOL();
        $line .= '"sku": "' . $row->sku . '",' . self::PHP_MY_EOL();

        if (count($row->more_photos) > 1) {
            $line .= '"image": [' . self::PHP_MY_EOL();
            $line .= '"https://example.com/photos/16x9/trinket.jpg",' . self::PHP_MY_EOL();
            $line .= '"https://example.com/photos/4x3/trinket.jpg",' . self::PHP_MY_EOL();
            $line .= '"https://example.com/photos/1x1/trinket.jpg"' . self::PHP_MY_EOL();
            $line .= '],' . self::PHP_MY_EOL();
        } else {
            $line .= '"image": "' . $Photo . '",' . self::PHP_MY_EOL();
        }


        $line .= '"name": "' . $row->name . '",' . self::PHP_MY_EOL();
        $line .= '"description": "' . AdminHelper::seoDesClean($row->des) . '",' . self::PHP_MY_EOL();

        if ($row->brand->name ?? null) {
            $line .= '"brand": {' . self::PHP_MY_EOL();
            $line .= '"@type": "Brand",' . self::PHP_MY_EOL();
            $line .= '"name": "' . $row->brand->name . '"' . self::PHP_MY_EOL();
            $line .= '},' . self::PHP_MY_EOL();
        }

        if($row->on_stock){

            $line .= '"offers": {' . self::PHP_MY_EOL();
            $line .= '"@type": "Offer",' . self::PHP_MY_EOL();
            $line .= '"url": "'.$url.'",' . self::PHP_MY_EOL();
            $line .= '"itemCondition": "https://schema.org/NewCondition",' . self::PHP_MY_EOL();
            $line .= '"availability": "https://schema.org/InStock",' . self::PHP_MY_EOL();
            $line .= '"price": ' . $row->price . ',' . self::PHP_MY_EOL();
            $line .= '"priceCurrency": "EGP",' . self::PHP_MY_EOL();
            $line .= '"priceValidUntil": "' . Carbon::now()->addDays(30) . '",' . self::PHP_MY_EOL();

            $line .= '"hasMerchantReturnPolicy": {' . self::PHP_MY_EOL();
            $line .= '"@type": "MerchantReturnPolicy",' . self::PHP_MY_EOL();
            $line .= '"applicableCountry": "EG",' . self::PHP_MY_EOL();
            $line .= '"returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",' . self::PHP_MY_EOL();
            $line .= '"merchantReturnDays": 15,' . self::PHP_MY_EOL();
            $line .= '"returnMethod": "https://schema.org/ReturnByMail",' . self::PHP_MY_EOL();
            $line .= '"returnFees": "https://schema.org/FreeReturn"' . self::PHP_MY_EOL();
            $line .= '},' . self::PHP_MY_EOL();

            $line .= '"shippingDetails": {' . self::PHP_MY_EOL();
            $line .= '"@type": "OfferShippingDetails",' . self::PHP_MY_EOL();
            $line .= '"shippingRate": {' . self::PHP_MY_EOL();
            $line .= '"@type": "MonetaryAmount",' . self::PHP_MY_EOL();
            $line .= '"value": 300,' . self::PHP_MY_EOL();
            $line .= '"currency": "EGP"' . self::PHP_MY_EOL();
            $line .= '},' . self::PHP_MY_EOL();

            $line .= '"shippingDestination": {' . self::PHP_MY_EOL();
            $line .= '"@type": "DefinedRegion",' . self::PHP_MY_EOL();
            $line .= '"addressCountry": "EG"' . self::PHP_MY_EOL();
            $line .= '},' . self::PHP_MY_EOL();

            $line .= '"deliveryTime": {' . self::PHP_MY_EOL();
            $line .= '"@type": "ShippingDeliveryTime",' . self::PHP_MY_EOL();
            $line .= '"handlingTime": {' . self::PHP_MY_EOL();
            $line .= '"@type": "QuantitativeValue",' . self::PHP_MY_EOL();
            $line .= '"minValue": 0,' . self::PHP_MY_EOL();
            $line .= '"maxValue": 1,' . self::PHP_MY_EOL();
            $line .= '"unitCode": "DAY"' . self::PHP_MY_EOL();
            $line .= '},' . self::PHP_MY_EOL();

            $line .= '"transitTime": {' . self::PHP_MY_EOL();
            $line .= '"@type": "QuantitativeValue",' . self::PHP_MY_EOL();
            $line .= '"minValue": 1,' . self::PHP_MY_EOL();
            $line .= '"maxValue": 5,' . self::PHP_MY_EOL();
            $line .= '"unitCode": "DAY"' . self::PHP_MY_EOL();
            $line .= '}' . self::PHP_MY_EOL();
            $line .= '}' . self::PHP_MY_EOL();
            $line .= '}' . self::PHP_MY_EOL();
            $line .= '},' . self::PHP_MY_EOL();

            $line .= '"review": {' . self::PHP_MY_EOL();
            $line .= '"@type": "Review",' . self::PHP_MY_EOL();
            $line .= '"reviewRating": {' . self::PHP_MY_EOL();
            $line .= '"@type": "Rating",' . self::PHP_MY_EOL();
            $line .= '"ratingValue": 4,' . self::PHP_MY_EOL();
            $line .= '"bestRating": 5' . self::PHP_MY_EOL();
            $line .= '},' . self::PHP_MY_EOL();

            $line .= '"author": {' . self::PHP_MY_EOL();
            $line .= '"@type": "Person",' . self::PHP_MY_EOL();
            $line .= '"name": "' . $this->WebConfig->translate($this->lang)->name . '"' . self::PHP_MY_EOL();
            $line .= '}' . self::PHP_MY_EOL();

            $line .= '},' . self::PHP_MY_EOL();
        }

        $line .= '"aggregateRating": {' . self::PHP_MY_EOL();
        $line .= '"@type": "AggregateRating",' . self::PHP_MY_EOL();
        $line .= '"ratingValue": 4.4,' . self::PHP_MY_EOL();
        $line .= '"reviewCount": 89' . self::PHP_MY_EOL();
        $line .= '}' . self::PHP_MY_EOL();


        $line .= '}</script>' . self::PHP_MY_EOL();

        return $line;

    }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||


    /*
    #@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    #|||||||||||||||||||||||||||||||||||||| # Units
        public function Units($row) {

            $Photo = getPhotoPath($row->photo, 'project', "photo");
            $url = urldecode(route("page_ListView", $row->slug));
            $This_AreaName = $row->locationName->name;
            $name = self::Clean($row->translate($this->lang)->name);
            $description = self::Clean($row->translate($this->lang)->g_des);
            if ($description == null) {
                $description = AdminHelper::seoDesClean($row->translate($this->lang)->des);
            }

            if ($row->listing_type == 'Unit') {
                $latitude = $row->projectName->latitude;
                $longitude = $row->projectName->longitude;
                $rowAmenity = $row->projectName->amenity;
            } else {
                $latitude = $row->latitude;
                $longitude = $row->longitude;
                $rowAmenity = $row->amenity;
            }

            $line = '<script type="application/ld+json">' . self::PHP_MY_EOL();
            $line .= '{' . self::PHP_MY_EOL();
            $line .= '"@context": "https://schema.org",' . self::PHP_MY_EOL();
            $line .= '"@id": "' . $url . '",' . self::PHP_MY_EOL();
            $line .= '"@type": ["ApartmentComplex","RealEstateListing","House"] ,' . self::PHP_MY_EOL();
            $line .= '"name": "' . $name . '",' . self::PHP_MY_EOL();
            $line .= '"description": "' . $description . '",' . self::PHP_MY_EOL();
            $line .= '"image": "' . $Photo . '",' . self::PHP_MY_EOL();
            $line .= '"url": "' . $url . '",' . self::PHP_MY_EOL();

            if (intval($row->area) > 0) {
                $line .= '"floorSize": {' . self::PHP_MY_EOL();
                $line .= '"@type": "QuantitativeValue",' . self::PHP_MY_EOL();
                $line .= '"value": ' . $row->area . ',' . self::PHP_MY_EOL();
                $line .= '"unitCode": "sqm"' . self::PHP_MY_EOL();
                $line .= '},' . self::PHP_MY_EOL();
            }

            if (intval($row->baths) > 0) {
                $line .= '"numberOfBathroomsTotal": ' . $row->baths . ',' . self::PHP_MY_EOL();
            }

            if (intval($row->rooms) > 0) {
                $line .= '"numberOfBedrooms": ' . $row->rooms . ' ,' . self::PHP_MY_EOL();
            }

            $line .= '"address": {"@type": "PostalAddress","addressCountry": "EG","addressRegion": "' . $This_AreaName . '"},' . self::PHP_MY_EOL();
            if ($latitude != null and $longitude != null) {
                $line .= '"geo": {"@type": "GeoCoordinates","latitude": "' . $latitude . '","longitude": "' . $longitude . '"},' . self::PHP_MY_EOL();
            }

            if (intval($row->price) > 0) {
                $line .= '"offers":[{"@type":"Offer","priceSpecification":{"@type":"UnitPriceSpecification","price":' . $row->price . ',"priceCurrency":"EGP","unitText":"sell"}}],' . self::PHP_MY_EOL();
            }

            if (is_array($rowAmenity) and count($rowAmenity) > 0) {
                $line .= '"amenityFeature": [';
                foreach ($this->amenities as $amenity) {
                    if (in_array($amenity->id, $rowAmenity)) {
                        $line .= '{"@type":"LocationFeatureSpecification","value": "true","name":"' . $amenity->name . '"},' . self::PHP_MY_EOL();
                    }
                }
                $line .= '{"@type":"LocationFeatureSpecification","value": "true","name":"' . $this->lastamenities . '"}' . self::PHP_MY_EOL();
                $line .= ' ],';
            }

            $line .= '"telephone": "' . $this->WebConfig->phone_call . '"' . self::PHP_MY_EOL();

            $line .= '}' . self::PHP_MY_EOL();
            $line .= '</script>' . self::PHP_MY_EOL();

            return $line;

        }

    #@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    #||||||||||||||||||||||||||||||||||||||||||||| #  Project
        public function Project($row) {

            $Photo = getPhotoPath($row->photo, 'project', "photo");
            $url = urldecode(route("page_ListView", $row->slug));
            $This_AreaName = $row->locationName->name;
            $name = self::Clean($row->translate($this->lang)->name);
            $description = self::Clean($row->translate($this->lang)->g_des);
            if ($description == null) {
                $description = AdminHelper::seoDesClean($row->translate($this->lang)->des);
            }

            $line = '<script type="application/ld+json">' . self::PHP_MY_EOL();
            $line .= "{" . self::PHP_MY_EOL();
            $line .= '"@context": "https://schema.org",' . self::PHP_MY_EOL();
            $line .= '"@type": "SingleFamilyResidence",' . self::PHP_MY_EOL();
            $line .= '"name": "' . $name . '",' . self::PHP_MY_EOL();
            $line .= '"description": "' . $description . '",' . self::PHP_MY_EOL();
            $line .= '"image": "' . $Photo . '",' . self::PHP_MY_EOL();
            $line .= '"url": "' . $url . '",' . self::PHP_MY_EOL();

            $line .= '"address": {"@type": "PostalAddress","addressCountry": "EG","addressRegion": "' . $This_AreaName . '"},' . self::PHP_MY_EOL();

            if ($row->latitude != null and $row->longitude != null) {
                $line .= '"geo": {"@type": "GeoCoordinates","latitude": "' . $row->latitude . '","longitude": "' . $row->longitude . '"},' . self::PHP_MY_EOL();
            }

            if (intval($row->price) > 0) {
                $line .= '"offers":[{"@type":"Offer","priceSpecification":{"@type":"UnitPriceSpecification","price":' . $row->price . ',"priceCurrency":"EGP","unitText":"sell"}}],' . self::PHP_MY_EOL();
            }

            if (is_array($row->amenity) and count($row->amenity) > 0) {
                $line .= '"amenityFeature": [';
                foreach ($this->amenities as $amenity) {
                    if (in_array($amenity->id, $row->amenity)) {
                        $line .= '{"@type":"LocationFeatureSpecification","value": "true","name":"' . $amenity->name . '"},' . self::PHP_MY_EOL();
                    }
                }
                $line .= '{"@type":"LocationFeatureSpecification","value": "true","name":"' . $this->lastamenities . '"}' . self::PHP_MY_EOL();
                $line .= ' ],';
            }

            $line .= '"telephone": "' . $this->WebConfig->phone_call . '"' . self::PHP_MY_EOL();
            $line .= "}" . self::PHP_MY_EOL();
            $line .= '</script>' . self::PHP_MY_EOL();

            return $line;

        }
    #@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    #||||||||||||||||||||||||||||||||||||||||||||| #  Video
        public function Video($row) {

            $publisher_logo = getDefPhotoPath($this->DefPhotoList, 'logo', 'photo');
            $Photo = getPhotoPath($row->photo, 'project', "photo");
            $PageUrl = url()->full();
            $embedUrl = "https://www.youtube.com/embed/" . $row['youtube_url'];

            $line = '<script type="application/ld+json">' . self::PHP_MY_EOL();
            $line .= '{' . self::PHP_MY_EOL();
            $line .= '"@context": "https://schema.org",' . self::PHP_MY_EOL();
            $line .= '"@type": "VideoObject",' . self::PHP_MY_EOL();
            $line .= '"name": "' . self::Clean($row->translate($this->lang)->name) . '",' . self::PHP_MY_EOL();
            $line .= '"description": "' . self::Clean($row->translate($this->lang)->g_des) . '",' . self::PHP_MY_EOL();
            $line .= '"thumbnailUrl": "' . $Photo . '",' . self::PHP_MY_EOL();
            $line .= '"uploadDate": "' . date(DATE_ATOM, strtotime($row->created_at)) . '",' . self::PHP_MY_EOL();

            $line .= '"publisher": {' . self::PHP_MY_EOL();
            $line .= '"@type": "Organization",' . self::PHP_MY_EOL();
            $line .= '"name": "' . $this->WebConfig->translate($this->lang)->name . '",' . self::PHP_MY_EOL();
            $line .= '"logo": {' . self::PHP_MY_EOL();
            $line .= '"@type": "ImageObject",' . self::PHP_MY_EOL();
            $line .= '"url": "' . $publisher_logo . '"' . self::PHP_MY_EOL();
            $line .= '}' . self::PHP_MY_EOL();
            $line .= '},' . self::PHP_MY_EOL();

            $line .= '"embedUrl": "' . $embedUrl . '",' . self::PHP_MY_EOL();
            $line .= '"contentUrl": "' . $PageUrl . '"' . self::PHP_MY_EOL();
            $line .= '}' . self::PHP_MY_EOL();
            $line .= '</script>' . self::PHP_MY_EOL();

            return $line;
        }



    */


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function PHP_MY_EOL() {
        if ($this->setBr) {
            return PHP_EOL;
        }
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function Clean($Text) {
        $Text = self::strip_tags_content($Text);
        $Text = preg_replace("/\r|\n/", " ", $Text);
        return $Text;
    }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
    public function strip_tags_content($text, $tags = '', $invert = FALSE) {
        preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
        $tags = array_unique($tags[1]);
        if (is_array($tags) and count($tags) > 0) {
            if ($invert == FALSE) {
                return preg_replace('@<(?!(?:' . implode('|', $tags) . ')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
            } else {
                return preg_replace('@<(' . implode('|', $tags) . ')\b.*?>.*?</\1>@si', '', $text);
            }
        } elseif ($invert == FALSE) {
            return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
        }
        return $text;
    }

}
