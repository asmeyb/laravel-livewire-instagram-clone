# Design Document

## Overview

This design addresses two distinct but related UI issues in the Instagram-like application:

1. **Swiper Loop Mode Issue**: Conditional loop mode configuration based on slide count to eliminate console warnings
2. **Create Modal Issue**: Proper modal display and interaction behavior for the post creation component

Both issues stem from configuration problems rather than fundamental architectural flaws. The solutions involve adjusting component initialization logic and modal configuration settings.

## Architecture

### Component Structure

```
Post Display System
├── Post Item Component (Livewire)
│   ├── Swiper Carousel (Alpine.js)
│   │   ├── Media Slides (Dynamic)
│   │   ├── Navigation Controls (Conditional)
│   │   └── Pagination Indicators
│   └── Post Metadata
│
Create Post System
├── Sidebar Component
│   └── Create Button (Modal Trigger)
├── Create Modal Component (Livewire)
│   ├── Media Upload Section
│   ├── Caption/Details Section
│   └── Advanced Settings
└── Wire Elements Modal Package
    ├── Modal Backdrop
    └── Modal Container
```

### Data Flow

**Swiper Initialization Flow:**
```
Post Data → Media Collection → Count Check → Swiper Config → Initialize
```

**Modal Interaction Flow:**
```
User Click → Alpine Event → Livewire Dispatch → Modal Open → User Interaction → Close/Submit
```

## Components and Interfaces

### 1. Swiper Component Enhancement

**File**: `resources/views/livewire/post/item.blade.php`

**Current Issue**: 
- Loop mode is hardcoded to `true`
- No slide count validation before initialization
- Navigation controls shown even for single slides

**Design Solution**:
- Calculate media count in Blade template
- Pass count to Alpine.js component
- Conditionally configure Swiper based on count
- Use Alpine.js reactive data for configuration

**Interface**:
```javascript
// Alpine.js data structure
{
    mediaCount: number,
    swiperConfig: {
        modules: Array,
        loop: boolean,  // Dynamic based on mediaCount
        pagination: Object,
        navigation: Object
    }
}
```

### 2. Create Modal Component Fix

**Files**: 
- `app/Livewire/Post/Create.php`
- `resources/views/livewire/post/create.blade.php`
- `resources/views/components/layouts/app/sidebar.blade.php`
- `config/wire-elements-modal.php`

**Current Issue**:
- Modal appears briefly then dims/fades
- Backdrop click may be closing modal immediately
- Possible event propagation issue

**Design Solution**:
- Review and adjust `close_modal_on_click_away` configuration
- Ensure proper event handling in modal trigger
- Verify modal component extends ModalComponent correctly
- Add proper z-index layering

**Configuration Changes**:
```php
// wire-elements-modal.php adjustments
'component_defaults' => [
    'modal_max_width' => '2xl',
    'close_modal_on_click_away' => false,  // Prevent accidental closes
    'close_modal_on_escape' => true,
    'close_modal_on_escape_is_forceful' => true,
    'dispatch_close_event' => false,
    'destroy_on_close' => false,
]
```

## Data Models

### Post Media Structure
```php
Post {
    id: integer
    user_id: integer
    description: string
    location: string
    type: enum('post', 'reel')
    media: Collection<Media>  // Relationship
}

Media {
    id: integer
    url: string
    mime: enum('image', 'video')
    mediable_id: integer
    mediable_type: string
}
```

### Swiper Configuration Model
```javascript
{
    modules: [Navigation, Pagination],
    loop: boolean,  // true if mediaCount >= 2, false otherwise
    pagination: {
        el: '.swiper-pagination'
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
    },
    slidesPerView: 1,
    spaceBetween: 0
}
```

## Error Handling

### Swiper Initialization Errors

**Scenario 1**: Empty media collection
- **Handling**: Don't initialize Swiper, show placeholder message
- **User Feedback**: "No media available"

**Scenario 2**: Single media item
- **Handling**: Initialize Swiper with loop: false, hide navigation
- **User Feedback**: Display single image/video without controls

**Scenario 3**: Multiple media items
- **Handling**: Initialize Swiper with loop: true, show navigation
- **User Feedback**: Full carousel functionality

### Modal Interaction Errors

**Scenario 1**: Modal fails to open
- **Handling**: Log error to console, show fallback UI
- **User Feedback**: "Unable to open create post. Please refresh."

**Scenario 2**: Modal closes unexpectedly
- **Handling**: Preserve form data in Livewire component
- **User Feedback**: Reopen modal with preserved data

**Scenario 3**: File upload fails
- **Handling**: Show validation error, keep modal open
- **User Feedback**: Display specific error message

## Testing Strategy

### Unit Tests

**Swiper Configuration Logic**:
```php
// Test cases
- testSwiperLoopDisabledForZeroSlides()
- testSwiperLoopDisabledForOneSlide()
- testSwiperLoopEnabledForTwoSlides()
- testSwiperLoopEnabledForMultipleSlides()
- testNavigationHiddenForSingleSlide()
- testNavigationVisibleForMultipleSlides()
```

**Modal Component**:
```php
// Test cases
- testModalOpensOnButtonClick()
- testModalStaysOpenAfterBackdropClick()
- testModalClosesOnEscapeKey()
- testModalClosesOnExplicitClose()
- testFormDataPreservedOnReopen()
```

### Integration Tests

**Post Display Flow**:
1. Load post with no media → Verify no Swiper initialization
2. Load post with one media → Verify Swiper without loop
3. Load post with multiple media → Verify Swiper with loop
4. Navigate through slides → Verify no console errors

**Create Post Flow**:
1. Click create button → Verify modal opens fully
2. Click backdrop → Verify modal stays open
3. Upload files → Verify preview displays
4. Submit form → Verify modal closes and post created
5. Click create again → Verify modal opens with clean state

### Browser Testing

**Console Error Monitoring**:
- Monitor for Swiper loop warnings
- Verify no JavaScript errors on modal open
- Check for event listener leaks

**Visual Regression**:
- Screenshot comparison for modal states
- Verify carousel appearance across different media counts
- Test responsive behavior on mobile/tablet/desktop

## Implementation Notes

### Swiper Fix Priority
1. Add media count calculation in Blade
2. Update Alpine.js initialization with conditional config
3. Test with various media counts
4. Verify console is clean

### Modal Fix Priority
1. Update wire-elements-modal config
2. Test modal open/close behavior
3. Verify backdrop interaction
4. Add z-index fixes if needed
5. Test form submission flow

### Performance Considerations
- Swiper initialization should be deferred until needed
- Modal should use lazy loading for heavy components
- File uploads should show progress indicators
- Consider virtual scrolling for large media collections

### Browser Compatibility
- Test Swiper on Safari, Chrome, Firefox, Edge
- Verify modal behavior on mobile browsers
- Ensure touch gestures work on mobile devices
- Test keyboard navigation for accessibility
