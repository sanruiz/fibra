# Soma Theme Modernization - Migration Plan

## Overview

This document outlines the comprehensive plan to modernize the Soma WordPress theme from its current state (v2.0.7) to a modern PSR-4 compliant architecture inspired by the WellSpring theme, while preserving its unique ACF flexible content page builder system.

**Project**: Soma Theme → Soma v3.0.0 (Modernized)  
**Start Date**: December 11, 2025  
**Target Completion**: TBD  
**Current Version**: 2.0.7  
**Target Version**: 3.0.0

---

## Current State Analysis

### ✅ Strengths to Preserve
- **ACF Flexible Content Page Builder**: Unique and powerful system with 50+ partials
- **Component-Based Architecture**: Modular partial system
- **Webpack Build System**: Asset compilation working
- **REST API Integration**: Custom endpoints at `/wp-json/soma/*`
- **Multilingual Support**: WP Multilang integration
- **4 Custom Post Types**: Portfolio, News, Careers, Team Members

### ❌ Areas Requiring Modernization
- **No PSR-4 Compliance**: Files use underscores, no namespace structure
- **Legacy PHP Patterns**: No PHP 8.1+ features (enums, match, first-class callables)
- **No Singleton Pattern**: Inconsistent class instantiation
- **No LoadableInterface**: No standardized component loading system
- **Mixed Concerns**: Business logic scattered across files
- **No Helper Functions**: Functionality buried in templates
- **No Centralized CSS Variables**: Hardcoded values throughout SCSS files
- **No Testing Infrastructure**: Zero unit tests or quality gates
- **No Elementor Integration**: Missing modern page builder support
- **Limited Documentation**: Only basic README

---

## Migration Phases

### **Phase 1: Foundation & Infrastructure** (Weeks 1-2)
**Goal**: Establish modern development foundation without breaking existing functionality

#### 1.1 Composer & Dependencies Setup
- [ ] Create `composer.json` with PSR-4 autoloading
- [ ] Configure namespace: `Soma\` → `includes/`
- [ ] Add development dependencies:
  - [ ] PHPUnit for testing
  - [ ] PHPCS (WordPress Coding Standards)
  - [ ] PHPStan (Level 6 initially, target Level 8)
  - [ ] PHPCBF (auto-fixing)
- [ ] Create `phpstan.neon` configuration
- [ ] Create `phpcs.xml` configuration
- [ ] Create `phpunit.xml` configuration

#### 1.2 Quality Assurance Infrastructure
- [ ] Create `tests/` directory structure:
  - [ ] `tests/Unit/`
  - [ ] `tests/Integration/`
  - [ ] `tests/Mocks/`
  - [ ] `tests/bootstrap.php`
- [ ] Create validation script: `scripts/validate-theme.sh`
- [ ] Set up Git pre-commit hooks
- [ ] Create `.editorconfig` for consistent formatting

#### 1.3 Core Architecture Files
- [ ] Create `includes/Core/` directory
- [ ] Create `includes/Core/Interfaces/LoadableInterface.php`
- [ ] Create `includes/Core/Loader.php` (component loader)
- [ ] Create `includes/Core/Theme.php` (main theme class)
- [ ] Migrate `functions.php` to use new Theme class

**Deliverables**: Working Composer setup, basic PSR-4 structure, quality tools configured

---

### **Phase 2: Module Migration** (Weeks 3-5)
**Goal**: Migrate existing functionality to PSR-4 structure

#### 2.1 Post Types Migration
- [ ] Create `includes/PostTypes/` directory structure:
  - [ ] `includes/PostTypes/Loader.php` (implements LoadableInterface)
  - [ ] `includes/PostTypes/Types/Portfolio.php`
  - [ ] `includes/PostTypes/Types/News.php`
  - [ ] `includes/PostTypes/Types/Careers.php`
  - [ ] `includes/PostTypes/Types/TeamMembers.php`
- [ ] Implement singleton pattern for each post type
- [ ] Add first-class callables for WordPress hooks
- [ ] Migrate from `inc/post-types.php`
- [ ] Create unit tests for each post type
- [ ] Update with modern PHP 8.1+ features

#### 2.2 Custom Fields Migration (ACF)
- [ ] Create `includes/CustomFields/` directory structure:
  - [ ] `includes/CustomFields/Loader.php` (implements LoadableInterface)
  - [ ] `includes/CustomFields/Fields/PortfolioCustomFields.php`
  - [ ] `includes/CustomFields/Fields/NewsCustomFields.php`
  - [ ] `includes/CustomFields/Fields/CareersCustomFields.php`
  - [ ] `includes/CustomFields/Fields/TeamMembersCustomFields.php`
- [ ] Migrate existing ACF field definitions
- [ ] Implement singleton pattern
- [ ] Add ACF dependency checks
- [ ] Keep ACF JSON sync functionality

#### 2.3 REST API Migration
- [ ] Create `includes/API/` directory structure:
  - [ ] `includes/API/Loader.php` (implements LoadableInterface)
  - [ ] `includes/API/Endpoints/NewsEndpoint.php`
  - [ ] `includes/API/Endpoints/CareersEndpoint.php`
  - [ ] `includes/API/Endpoints/PortfolioEndpoint.php`
  - [ ] `includes/API/Endpoints/DocumentsEndpoint.php`
  - [ ] `includes/API/Endpoints/EventsEndpoint.php`
- [ ] Migrate from `inc/endpoints.php`
- [ ] Add proper permission callbacks
- [ ] Implement response formatting
- [ ] Add API documentation

#### 2.4 CF7 Integration Migration
- [ ] Create `includes/CF7/` directory structure:
  - [ ] `includes/CF7/Loader.php` (implements LoadableInterface)
  - [ ] `includes/CF7/Validations.php` (from cf7-validations.php)
- [ ] Implement modern validation patterns
- [ ] Add unit tests for validation logic

**Deliverables**: All modules migrated to PSR-4 with tests, old files can be deleted

---

### **Phase 3: Utilities & Helpers** (Week 6)
**Goal**: Centralize and standardize utility functions

#### 3.1 Helper Functions System
- [ ] Create `includes/Utils/` directory structure:
  - [ ] `includes/Utils/Helpers.php` (centralized global functions)
  - [ ] `includes/Utils/Logger.php` (PSR-3 compliant logging)
  - [ ] `includes/Utils/Cache.php` (caching utilities)
  - [ ] `includes/Utils/CacheInvalidationManager.php`
- [ ] Implement `soma_` prefixed global helper functions
- [ ] Create Logger system with 8 PSR-3 log levels
- [ ] Add cache system with tag-based invalidation
- [ ] Migrate scattered helper code from partials

#### 3.2 Enums Implementation
- [ ] Create `includes/Core/Enums/` directory:
  - [ ] `includes/Core/Enums/PostType.php` (4 post types)
- [ ] Create `includes/Utils/Enums/` directory:
  - [ ] `includes/Utils/Enums/LogLevel.php` (8 PSR-3 levels)
  - [ ] `includes/Utils/Enums/CacheTag.php` (cache tags)
- [ ] Replace all magic strings with enum values
- [ ] Add helper methods to enums

**Deliverables**: Centralized utility system, modern PHP enums, logging and caching

---

### **Phase 4: Elementor Integration** (Weeks 7-9)
**Goal**: Add Elementor support while keeping ACF flexible content system

#### 4.1 Elementor Infrastructure
- [ ] Create `includes/Elementor/` directory structure:
  - [ ] `includes/Elementor/Loader.php` (implements LoadableInterface)
  - [ ] `includes/Elementor/Widgets/` (custom widgets)
- [ ] Register custom Elementor category: 'soma'
- [ ] Implement widget base class
- [ ] Add Elementor dependency checks

#### 4.2 Priority Widgets Migration
Convert most-used partials to Elementor widgets:
- [ ] **Navbar Widget** (from Navbar.php partial)
- [ ] **Footer Widget** (from Footer.php partial)
- [ ] **Business Units Widget** (from BusinessUnits.php)
- [ ] **Services Widget** (grid display)
- [ ] **Team Members Widget** (grid display)
- [ ] **News List Widget** (from NewsList.php)
- [ ] **Portfolio Widget** (from Portfolio.php)
- [ ] **Contact Form Widget** (CF7 integration)

#### 4.3 Widget Features
For each widget:
- [ ] Implement Elementor controls (content, style, advanced)
- [ ] Add typography controls using Elementor native system
- [ ] Use CSS variables for styling
- [ ] Include proper escaping and security
- [ ] Add ACF integration where needed
- [ ] Create corresponding CSS files in `assets/css/widgets/`

**Deliverables**: 8+ Elementor widgets, backward compatibility with ACF system

---

### **Phase 5: CSS Variables System** (Week 10)
**Goal**: Centralize design system with CSS custom properties

#### 5.1 CSS Variables Foundation
- [ ] Create `assets/css/variables.css` with:
  - [ ] Colors (primary, secondary, text, backgrounds)
  - [ ] Typography (font families, sizes, weights)
  - [ ] Spacing (xs, sm, md, lg, xl scale)
  - [ ] Layout (borders, shadows, transitions, breakpoints)
  - [ ] Component-specific variables
- [ ] Create naming convention: `--soma-[category]-[property]-[variant]`
- [ ] Document all variables in `docs/CSS_VARIABLES.md`

#### 5.2 SCSS Migration
- [ ] Replace hardcoded values in all `sass/partials/_*.scss` files
- [ ] Update `sass/main.scss` to import variables
- [ ] Create mixins for common patterns
- [ ] Ensure backward compatibility
- [ ] Test all components visually

**Deliverables**: 200+ CSS variables, modernized SCSS architecture

---

### **Phase 6: Page Builder Enhancement** ✅ (Week 11) **COMPLETE**
**Goal**: Improve existing ACF flexible content system  
**Completed**: December 11-12, 2025

#### 6.1 Page Builder Refactoring
- [x] Create `includes/PageBuilder/` directory:
  - [x] `includes/PageBuilder/Loader.php` (implements LoadableInterface) - 235 lines
  - [x] `includes/PageBuilder/BlockRegistry.php` (centralized block mapping) - 236 lines, 53 blocks
  - [x] `includes/PageBuilder/BlockRenderer.php` (rendering logic) - 334 lines
- [x] Migrate `page-builder.php` to class-based system (110+ lines → 34 lines)
- [x] Add block validation and error handling (multi-layer validation)
- [x] Implement block caching where appropriate (tag-based with auto-invalidation)
- [x] **Breaking Change**: Removed global variables, implemented WordPress query vars

#### 6.2 Partial System Enhancement
- [x] Keep all existing partials in `partials/` directory (53 partials)
- [x] Add proper docblocks to partials (3 completed, template created for remaining 50)
- [x] Implement error handling (PSR-3 logging, graceful failures)
- [x] **Breaking Change**: Removed `$pageBlock`, using `get_query_var()` instead
- [x] Create partial documentation (PHPDoc template, TESTING_GUIDE.md)

#### 6.3 Testing Infrastructure (Bonus)
- [x] Admin UI test page (6 categories, 23 tests)
- [x] PHPUnit integration tests (19 test methods)
- [x] WP-CLI test runner (bash script)
- [x] TESTING_GUIDE.md (337 lines)

#### 6.4 Quality Validation (Bonus)
- [x] PHPCS: 624 → 154 errors (470 auto-fixed)
- [x] PHPStan Level 6: 0 critical errors
- [x] phpstan-baseline.neon for 3 acceptable warnings
- [x] 41 files formatted with PHPCBF

**Deliverables**: ✅ Enhanced page builder with PSR-4 architecture, 53 blocks registered, comprehensive testing, quality validated

**Documentation**: `docs/PHASE_6_COMPLETION.md` (1100+ lines)

---

### **Phase 7: Testing & Quality** (Week 12)
**Goal**: Achieve 80%+ test coverage and quality compliance

#### 7.1 Unit Tests
- [ ] Core components (Loader, Theme)
- [ ] Post Types (all 4 types)
- [ ] Custom Fields (all 4 field groups)
- [ ] API Endpoints (all 5 endpoints)
- [ ] Utilities (Helpers, Logger, Cache)
- [ ] Elementor Widgets (all widgets)

#### 7.2 Integration Tests
- [ ] WordPress hooks integration
- [ ] ACF integration
- [ ] Elementor integration
- [ ] CF7 integration
- [ ] Page builder rendering

#### 7.3 Quality Gates
- [ ] PHPCS: 0 errors (warnings acceptable)
- [ ] PHPStan: Level 8 compliance
- [ ] PHPUnit: 80%+ coverage
- [ ] Manual testing checklist
- [ ] Performance benchmarks

**Deliverables**: Comprehensive test suite, all quality gates passing

---

### **Phase 8: Documentation & Release** (Week 13)
**Goal**: Complete documentation and prepare v3.0.0 release

#### 8.1 Documentation
- [ ] Update `README.md` with new architecture
- [ ] Create `docs/ARCHITECTURE.md` (system overview)
- [ ] Create `docs/DEVELOPMENT.md` (developer guide)
- [ ] Create `docs/WIDGETS.md` (Elementor widgets guide)
- [ ] Create `docs/HELPERS.md` (helper functions reference)
- [ ] Create `docs/CSS_VARIABLES.md` (design system)
- [ ] Create `docs/MIGRATION_FROM_V2.md` (upgrade guide)
- [ ] Update `.github/copilot-instructions.md`

#### 8.2 Version Management
- [ ] Create `scripts/update-version.sh` (from WellSpring)
- [ ] Update version to 3.0.0 across all files
- [ ] Create `CHANGELOG.md` with all changes
- [ ] Tag v3.0.0 release

#### 8.3 Backward Compatibility
- [ ] Test all existing pages and templates
- [ ] Verify ACF flexible content still works
- [ ] Check all REST endpoints
- [ ] Validate multilingual functionality
- [ ] Test custom post types

**Deliverables**: Complete documentation, v3.0.0 release ready

---

## Success Criteria

### Technical Requirements
- ✅ **PSR-4 Compliance**: All classes follow PSR-4 autoloading
- ✅ **PHP 8.1+ Features**: Enums, match expressions, first-class callables
- ✅ **LoadableInterface**: All modules implement standardized loading
- ✅ **Singleton Pattern**: Consistent instantiation across theme
- ✅ **Quality Gates**: PHPCS (0 errors), PHPStan (Level 8), 80% coverage
- ✅ **CSS Variables**: 200+ design tokens centralized
- ✅ **Elementor Integration**: 8+ custom widgets
- ✅ **Helper Functions**: Centralized utility system

### Functional Requirements
- ✅ **Backward Compatibility**: Existing sites work without changes
- ✅ **ACF System**: Flexible content builder fully functional
- ✅ **REST API**: All endpoints working
- ✅ **Performance**: No degradation from current version
- ✅ **Multilingual**: WP Multilang still supported
- ✅ **Custom Post Types**: All 4 types working correctly

### Documentation Requirements
- ✅ **Developer Guides**: Complete architecture and development docs
- ✅ **API Documentation**: Helper functions and widgets documented
- ✅ **Migration Guide**: Clear upgrade path from v2.x
- ✅ **Code Comments**: All classes and functions documented

---

## Risk Mitigation

### High Risk Areas
1. **Breaking Existing Sites**: Extensive testing required
   - Mitigation: Maintain parallel systems during migration
   - Test on staging before production deployment

2. **ACF Compatibility**: Field groups must remain functional
   - Mitigation: Keep ACF JSON, test all field groups
   - Maintain backward compatibility with old field keys

3. **Performance Degradation**: New architecture overhead
   - Mitigation: Implement caching, monitor performance
   - Benchmark against v2.0.7 baseline

4. **Learning Curve**: Team adaptation to new patterns
   - Mitigation: Comprehensive documentation
   - Code examples and best practices

### Rollback Strategy
- Keep v2.0.7 branch maintained
- Tag all migration milestones
- Document rollback procedures
- Test restoration process

---

## Timeline Summary

| Phase | Duration | Deliverables |
|-------|----------|--------------|
| Phase 1: Foundation | 2 weeks | Composer, PSR-4 structure, quality tools |
| Phase 2: Module Migration | 3 weeks | Post Types, Custom Fields, API, CF7 |
| Phase 3: Utilities | 1 week | Helpers, Logger, Cache, Enums |
| Phase 4: Elementor | 3 weeks | 8+ widgets, Elementor integration |
| Phase 5: CSS Variables | 1 week | 200+ design tokens |
| Phase 6: Page Builder | 1 week | Enhanced ACF system |
| Phase 7: Testing | 1 week | 80%+ coverage, quality gates |
| Phase 8: Documentation | 1 week | Complete docs, v3.0.0 release |
| **Total** | **13 weeks** | **Fully modernized theme** |

---

## Next Steps

1. Review and approve migration plan
2. Set up development environment
3. Create feature branch: `feature/v3-modernization`
4. Begin Phase 1: Foundation & Infrastructure
5. Weekly progress reviews and adjustments

---

**Document Version**: 1.0  
**Last Updated**: December 11, 2025  
**Status**: Planning Phase
