# Testing & Verification Guide

This document provides instructions for manually testing the new Show/Hide controls added to ShareQuotation and TeamMember widgets.

## Changes Summary

### ShareQuotation Widget
Added 4 new visibility controls in the Display Options section:
- Show Volume (default: hidden)
- Show Date (default: hidden)
- Show Change (default: hidden)
- Show Percentage (default: hidden)

### TeamMember Widget
Added 2 new features:
- Show Photo control (default: visible)
- Full card link (entire card is clickable)

---

## Testing Instructions

### ShareQuotation Widget Testing

#### 1. Access Widget in Elementor

1. Edit a page with Elementor
2. Add or edit a ShareQuotation widget
3. Open the widget settings panel

#### 2. Verify Controls Exist

In the **Display Options** tab, verify these controls appear:
- [ ] Dark Background (existing)
- [ ] Show Download Link (existing)
- [ ] **Show Volume** (NEW)
- [ ] **Show Date** (NEW)
- [ ] **Show Change** (NEW)
- [ ] **Show Percentage** (NEW)

#### 3. Test Default State (All Hidden)

By default, Volume, Date, Change, and Percentage should be OFF:
- [ ] Volume section (Column 3) should be empty but maintain space on desktop
- [ ] Date should not appear in Column 2
- [ ] Change/Percentage indicators should not appear in Column 2
- [ ] Only Price label and value should appear in Column 2

#### 4. Test Individual Controls

**Test Show Volume:**
1. Turn ON "Show Volume"
2. Verify: Volume label and value appear in Column 3
3. Turn OFF "Show Volume"
4. Verify: Volume section becomes empty but column space maintained (desktop)

**Test Show Date:**
1. Turn ON "Show Date"
2. Verify: Date/time appears in Column 2 below price
3. Turn OFF "Show Date"
4. Verify: Date disappears

**Test Show Change:**
1. Turn ON "Show Change" (keep Show Percentage OFF)
2. Verify: Only change value appears (e.g., "+$0.50" or "-$0.25")
3. Verify: Positive changes are green, negative changes are red

**Test Show Percentage:**
1. Turn OFF "Show Change"
2. Turn ON "Show Percentage"
3. Verify: Only percentage appears (e.g., "+3.39%" or "-2.50%")
4. Verify: Positive percentages are green, negative percentages are red

**Test Combined Change + Percentage:**
1. Turn ON both "Show Change" and "Show Percentage"
2. Verify: Combined format appears (e.g., "+$0.50 (+3.39%)")
3. Verify: Color coding applies to entire combined value

#### 5. Test Responsive Behavior

**Desktop (>991px):**
- [ ] 3-column layout maintained
- [ ] Empty Volume column still takes up space when hidden

**Tablet (768px-991px):**
- [ ] Column 2 and 3 are side-by-side (50% each)
- [ ] Column 1 (title/symbol) above

**Mobile (<767px):**
- [ ] All columns stack vertically
- [ ] Empty Volume column is hidden completely (saves space)

#### 6. Test with Stock Data

Verify the widget works with actual stock data:
1. Ensure stock data is configured in Settings
2. Verify formatting functions work correctly
3. Test with positive and negative values

---

### TeamMember Widget Testing

#### 1. Access Widget in Elementor

1. Edit a page with Elementor
2. Add or edit a TeamMember widget
3. Open the widget settings panel

#### 2. Verify Controls Exist

In the **Content** tab, verify these controls appear:
- [ ] Use Current Member (existing)
- [ ] Select Team Member (existing)
- [ ] **Show Photo** (NEW)
- [ ] Show Featured Text (existing)
- [ ] Show SOMA Logo (existing)

#### 3. Test Show Photo Control

**Default State (Photo Visible):**
1. Select a team member with a featured image
2. Verify: Photo appears in right column
3. Verify: "Show Photo" control is ON by default

**Hide Photo:**
1. Turn OFF "Show Photo"
2. Verify: Featured image disappears
3. Verify: Layout still works without image
4. Verify: Other content (name, position, bio) still displays correctly

**Show Photo Again:**
1. Turn ON "Show Photo"
2. Verify: Image reappears
3. Verify: Image has proper alt text (member name)

#### 4. Test Full Card Link

**With Current Member (URL detected):**
1. Set "Use Current Member" to ON
2. View the widget on a team member single page
3. Verify: Entire card is wrapped in a clickable link
4. Hover over card: Verify hover effect (opacity 0.9)
5. Hover over name: Verify name underlines
6. Click anywhere on card: Should navigate to member page

**With Selected Member:**
1. Set "Use Current Member" to OFF
2. Select a specific team member from dropdown
3. Verify: Entire card is clickable
4. Verify: Same hover effects apply
5. Click card: Should navigate to selected member's page

**Without Member (Editor Mode):**
1. In Elementor editor with no member selected
2. Verify: Shows appropriate warning message
3. Verify: No broken links or errors

#### 5. Test Responsive Behavior

**Desktop (>767px):**
- [ ] Two-column layout (Title/Featured Text left, Image/Body right)
- [ ] Card link covers entire content area
- [ ] Hover effects work smoothly

**Mobile (<767px):**
- [ ] Stacked layout (Title, Image, Featured Text, Body)
- [ ] Card link still covers all content
- [ ] Tap targets are appropriate size
- [ ] No duplicate links

#### 6. Test Accessibility

**Link Semantics:**
- [ ] Card link has proper href attribute
- [ ] Card uses semantic HTML (not just onclick)
- [ ] No nested links (only one link wrapping entire card)

**Keyboard Navigation:**
- [ ] Card is focusable with Tab key
- [ ] Focus indicator is visible
- [ ] Enter/Space activates the link

**Screen Reader:**
- [ ] Link announces as interactive element
- [ ] Member name is readable
- [ ] Image alt text is descriptive

---

## Edge Cases to Test

### ShareQuotation Widget

1. **All Controls OFF:**
   - Only title, symbol, and price should display
   - Layout should not break

2. **No Stock Data:**
   - Should show appropriate message to admins
   - Should not show errors to visitors

3. **Zero Values:**
   - Change: $0.00 (no +/- sign)
   - Percentage: 0.00% (no +/- sign)

4. **Large Numbers:**
   - Volume: 10,000,000+ (formatted with commas)
   - Price: Should handle decimal places correctly

### TeamMember Widget

1. **No Image:**
   - Member without featured image
   - Should show content without image section

2. **No Link:**
   - Widget outside member context
   - Should render content without card link

3. **Empty Fields:**
   - Member with empty biography
   - Member with empty featured text
   - Should handle gracefully

4. **Long Content:**
   - Long names (should wrap properly)
   - Long biographies (should display fully)

---

## Browser Testing

Test in these browsers to ensure compatibility:

- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile Safari (iOS)
- [ ] Mobile Chrome (Android)

---

## Quality Verification

Before marking complete, verify:

- [ ] PHPCS passes (0 errors)
- [ ] PHPStan Level 6+ passes (0 critical errors)
- [ ] All integration tests pass
- [ ] Translations generated and Spanish added
- [ ] WIDGETS.md documentation updated
- [ ] CHANGELOG.md updated
- [ ] No console errors in browser
- [ ] No PHP errors in debug.log

---

## Known Limitations

1. **Translation Files:** The `.pot`, `.po`, and `.mo` files need to be regenerated in a WordPress environment with WP-CLI. See `TRANSLATIONS_NEEDED.md` for instructions.

2. **PHPUnit Tests:** Tests cannot run in this environment without WordPress test suite installed. Tests should be run in a proper WordPress development environment.

---

## Rollback Instructions

If issues are found, the changes can be reverted:

```bash
# Revert the commit
git revert 798e3c7

# Or reset to previous state
git reset --hard 8c15295

# Force push if needed (use with caution)
git push -f origin branch-name
```

---

## Support

For questions or issues:
1. Check CHANGELOG.md for detailed changes
2. Review TRANSLATIONS_NEEDED.md for translation status
3. Refer to Elementor widget development guidelines
4. Check WordPress Coding Standards documentation
