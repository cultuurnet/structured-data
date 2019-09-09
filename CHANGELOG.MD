# Release notes for UDB Structured Data for Craft CMS 3.x

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).


## 1.2.0 - 2019-09-09

### Added
- API results for detail pages are now cached for 24 hours (caching is enable for any environment that is not `DEV`)
- This cache can be cleared for the CP's Clear cache utility (1 cache for all event detail data)

## 1.1.0 - 2019-08-05

### Added
- `getOfferData` now take a second parameter to enabled to showing of past events (applies `AvailableFrom` and `AvailableTo` filters to the API query)

## 1.0.0 - 2019-06-28

### Changed
- `getOfferData` now takes an optional `cdbid` as parameter and fall back to checking the url itself

### Added
- Added readme
- Added added changelog
