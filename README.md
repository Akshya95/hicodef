# HICODEF WordPress Theme — v2.0.0

**Himalayan Community Development Forum (हिमालयन सामुदायिक विकास मञ्च)**
Kawasoti-02, Shivabasti, Nawalparasi (Bardaghat Susta Purba), Nepal · Est. 2053 BS

---

## What's new in v2.0.0

- Homepage image **slider** with fixed height (images auto-fit via object-fit:cover)
- Slider **manager panel** — add slides with image upload + caption, remove slides
- Partner logos **carousel** — infinite auto-scrolling, hover to pause
- Partner carousel **manager** — add / remove logos with image upload, website URL, type
- Partners from WordPress Admin (Partners CPT) load automatically into the carousel
- Redesigned homepage with Lora serif + Inter sans typography
- Warm earth/forest/cream colour palette (not generic corporate green)
- Bikram Sambat (BS) calendar dates in news/publications
- Full Nepali name (हिमालयन सामुदायिक विकास मञ्च) in header, hero, footer, About

---

## Theme Files

| File | Purpose |
|------|---------|
| `style.css` | Theme metadata + all core styles |
| `functions.php` | CPTs, widgets, customizer, script/style registration |
| `front-page.php` | Homepage: slider, stats, thematic areas, projects, partner carousel, news |
| `index.php` | Blog listing |
| `single.php` | Single blog post (breadcrumbs, reading time, related posts) |
| `single-program.php` | Project detail page |
| `single-campaign.php` | Campaign detail with funding progress + donate amounts |
| `single-team_member.php` | Team member profile with social links |
| `page.php` | Standard pages |
| `page-donate.php` | Donate page template |
| `page-contact.php` | Contact page template (HICODEF address pre-filled) |
| `page-publications.php` | Filterable publications list with PDF download |
| `page-get-involved.php` | Volunteer, Donate, Partner, OJT + live vacancies |
| `page-policies.php` | Child Protection, Safeguarding, Financial, HR policies |
| `archive.php` | Generic CPT archives |
| `archive-vacancy.php` | Vacancies archive with deadline countdown |
| `sidebar.php` | Sidebar with donate widget |
| `comments.php` | Styled comments + reply form |
| `header.php` | Sticky header with mobile navigation |
| `footer.php` | Multi-column footer with HICODEF address |
| `searchform.php` | Custom search form |
| `404.php` | Not found page |
| `search.php` | Search results |
| `theme.json` | Block editor colour/font presets |
| `js/main.js` | Mobile menu, scroll animations, progress bars |
| `js/slider.js` | Homepage image slider + manager |
| `js/partners-carousel.js` | Partner logo carousel + manager |
| `css/slider.css` | Slider + carousel styles |
| `css/editor.css` | Gutenberg editor styles |
| `inc/template-tags.php` | Breadcrumbs, related posts, impact banner, reading time |
| `inc/customizer-colors.php` | Live colour pickers in Customizer |
| `images/partners/` | Default partner logo images |

---

## Custom Post Types

| Label | Slug | Archive URL |
|-------|------|-------------|
| Projects | `program` | `/projects/` |
| Campaigns | `campaign` | `/campaigns/` |
| Team Members | `team_member` | (no archive) |
| Publications | `publication` | `/publications/` |
| Partners & Donors | `partner` | (no archive — shows in carousel) |
| Vacancies | `vacancy` | `/vacancies/` |
| Success Stories | `story` | `/stories/` |

---

## Page Templates (Appearance → Page Attributes → Template)

| Template | Use for |
|----------|---------|
| Default | Standard pages |
| Donate Page | Donation landing page |
| Contact Page | Contact form + HICODEF address |
| Publications Page | Filterable publications list |
| Get Involved Page | Volunteer/Partner/OJT/Vacancies |
| Policies Page | Safeguarding / HR / Financial / Child Protection |

---

## Slider — How to use

1. Go to the homepage and click **"Manage slides"**
2. Upload a photo, add a title, tag line, and description
3. Click **"Add slide to homepage"**
4. Slides are stored in browser localStorage; for server-side storage, use the Slider CPT (coming in v2.1)

## Partner Carousel — How to use

**Option A (quick, browser-side):**
1. Click **"Manage partners"** on the homepage
2. Upload a logo, fill in name + URL, click **"Add to carousel"**

**Option B (permanent, server-side):**
1. WordPress Admin → Partners → Add New
2. Upload logo as Featured Image
3. Fill in partner type + website URL in the sidebar meta box
4. Publish — logo appears in the carousel automatically on next page load

---

## Contact Details (pre-filled)
- Address: Kawasoti-02, Shivabasti, Nawalparasi (Bardaghat Susta Purba)
- Gandaki Province, Nepal
- Email: info@hicodef.org
- Founded: 2053 BS (1996 AD)
- Registration: Social Welfare Council (SWC) of Nepal

## Known HICODEF Partners (fallback if no Partners CPT entries exist)
The Glacier Trust, CRS, SWC, Palikas, HELVETAS, EU

## Previous Projects (displayed on homepage)
LFAP · NCDP · LFP · MSFP · LGCDP · Micro Hydro · Improved Cooking Stove · PAUNCH · SAMI
