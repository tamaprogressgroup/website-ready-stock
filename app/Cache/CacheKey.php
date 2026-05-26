<?php

namespace App\Cache;

/**
 * Central registry of all Redis cache path identifiers.
 * Always use these constants instead of raw strings to prevent typos
 * and make it easy to track what is cached across the application.
 */
class CacheKey
{
    // =========================================================================
    // Master data — simple, non-URL-dependent
    // =========================================================================
    const BANNERS             = 'banners';
    const PROPERTY_TYPES      = 'property_types';
    const PROPERTY_CONDITIONS = 'property_conditions';
    const TAGS                = 'tags';
    const PROVINCES           = 'provinces';
    const TOWNSHIPS           = 'townships';
    const CLUSTERS            = 'clusters';

    // =========================================================================
    // Property data — reloadable from DB
    // =========================================================================
    const PROPERTY_UNITS      = 'property_units';   // All active units list
    const PROPERTY_UNIT       = 'property_unit';    // Single unit: property_unit:{id}

    // =========================================================================
    // Front-end page cache — URL/slug-dependent, flush-only on back-end change
    // These are repopulated automatically when visitors load the front-end pages.
    // =========================================================================
    const RECOMMENDATIONS     = 'recommendations';
    const NEW_PROPERTIES      = 'new_properties';
    const PROPERTY_DETAIL     = 'property_detail';
    const RELATED_PROPERTIES  = 'related_properties';
    const TOWNSHIPS_PROJECT   = 'townships_project';
    const ALL_PRODUCTS        = 'all_products';
    const PROPERTY_COUNT      = 'property_count';
}
