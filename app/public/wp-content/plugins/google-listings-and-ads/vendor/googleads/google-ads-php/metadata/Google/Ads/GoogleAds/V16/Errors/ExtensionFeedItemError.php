<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/ads/googleads/v16/errors/extension_feed_item_error.proto

namespace GPBMetadata\Google\Ads\GoogleAds\V16\Errors;

class ExtensionFeedItemError
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();
        if (static::$is_initialized == true) {
          return;
        }
        $pool->internalAddGeneratedFile(
            '
�
?google/ads/googleads/v16/errors/extension_feed_item_error.protogoogle.ads.googleads.v16.errors"�
ExtensionFeedItemErrorEnum"�
ExtensionFeedItemError
UNSPECIFIED 
UNKNOWN
VALUE_OUT_OF_RANGE
URL_LIST_TOO_LONG2
.CANNOT_HAVE_RESTRICTION_ON_EMPTY_GEO_TARGETING
CANNOT_SET_WITH_FINAL_URLS!
CANNOT_SET_WITHOUT_FINAL_URLS
INVALID_PHONE_NUMBER*
&PHONE_NUMBER_NOT_SUPPORTED_FOR_COUNTRY-
)CARRIER_SPECIFIC_SHORT_NUMBER_NOT_ALLOWED	#
PREMIUM_RATE_NUMBER_NOT_ALLOWED

DISALLOWED_NUMBER_TYPE(
$INVALID_DOMESTIC_PHONE_NUMBER_FORMAT#
VANITY_PHONE_NUMBER_NOT_ALLOWED"
INVALID_CALL_CONVERSION_ACTION.
*CUSTOMER_NOT_ON_ALLOWLIST_FOR_CALLTRACKING/*
&CALLTRACKING_NOT_SUPPORTED_FOR_COUNTRY0
,CUSTOMER_CONSENT_FOR_CALL_RECORDING_REQUIRED
INVALID_APP_ID&
"QUOTES_IN_REVIEW_EXTENSION_SNIPPET\'
#HYPHENS_IN_REVIEW_EXTENSION_SNIPPET&
"REVIEW_EXTENSION_SOURCE_INELIGIBLE(
$SOURCE_NAME_IN_REVIEW_EXTENSION_TEXT
INCONSISTENT_CURRENCY_CODES*
&PRICE_EXTENSION_HAS_DUPLICATED_HEADERS4
0PRICE_ITEM_HAS_DUPLICATED_HEADER_AND_DESCRIPTION%
!PRICE_EXTENSION_HAS_TOO_FEW_ITEMS&
"PRICE_EXTENSION_HAS_TOO_MANY_ITEMS
UNSUPPORTED_VALUE*
&UNSUPPORTED_VALUE_IN_SELECTED_LANGUAGE
INVALID_DEVICE_PREFERENCE
INVALID_SCHEDULE_END*
&DATE_TIME_MUST_BE_IN_ACCOUNT_TIME_ZONE 
INVALID_SNIPPETS_HEADER!\'
#CANNOT_OPERATE_ON_REMOVED_FEED_ITEM"<
8PHONE_NUMBER_NOT_SUPPORTED_WITH_CALLTRACKING_FOR_COUNTRY#(
$CONFLICTING_CALL_CONVERSION_SETTINGS$
EXTENSION_TYPE_MISMATCH%
EXTENSION_SUBTYPE_REQUIRED&
EXTENSION_TYPE_UNSUPPORTED\'1
-CANNOT_OPERATE_ON_FEED_WITH_MULTIPLE_MAPPINGS(.
*CANNOT_OPERATE_ON_FEED_WITH_KEY_ATTRIBUTES)
INVALID_PRICE_FORMAT*
PROMOTION_INVALID_TIME+%
!TOO_MANY_DECIMAL_PLACES_SPECIFIED,$
 CONCRETE_EXTENSION_TYPE_REQUIRED- 
SCHEDULE_END_NOT_AFTER_START.B�
#com.google.ads.googleads.v16.errorsBExtensionFeedItemErrorProtoPZEgoogle.golang.org/genproto/googleapis/ads/googleads/v16/errors;errors�GAA�Google.Ads.GoogleAds.V16.Errors�Google\\Ads\\GoogleAds\\V16\\Errors�#Google::Ads::GoogleAds::V16::Errorsbproto3'
        , true);
        static::$is_initialized = true;
    }
}

