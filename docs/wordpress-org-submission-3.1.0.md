# WordPress.org submission pack for 3.1.0

This document is the operator checklist for the first WordPress.org submission
of Npcink Site Toolbox. It does not grant or reserve a WordPress.org slug. Use
the slug in the approval email if it differs from the proposed slug below.

## Submission facts

| Field | Value |
| --- | --- |
| Plugin name | Npcink Site Toolbox |
| Proposed slug | `npcink-site-toolbox` |
| WordPress.org account | `muze233` |
| Stable version | `3.1.0` |
| Main plugin file | `npcink-site-toolbox.php` |
| Public source | <https://github.com/muze-page/npcink-site-toolbox> |
| Release | <https://github.com/muze-page/npcink-site-toolbox/releases/tag/v3.1.0> |
| Upload file | Locally verified `npcink-site-toolbox.zip` built from the audited submission commit |

The locally rebuilt ZIP is the current submission candidate. The existing
GitHub 3.1.0 release asset predates the contributor and screenshot metadata in
this pack; replace that asset after committing these changes before treating
the GitHub download as the submission source. The files in `.wordpress-org/`
are listing assets for the WordPress.org SVN repository and must not be added
to the upload ZIP or to SVN `trunk/`.

## Copy for the submission form

### Short description

Npcink Site Toolbox is an opt-in utility plugin for WordPress site owners. It
groups practical site, media, content, SEO, security, China-focused integration,
diagnostic, and maintenance controls into a task-oriented admin interface.

### Reviewer note

All modules that contact third parties are disabled by default and require an
administrator to enable the feature or run a connectivity check. The plugin
does not send developer telemetry. The `External Services` section in
`readme.txt` documents each provider, the data sent, the trigger, and links to
the provider terms and privacy policy. Reproducible front-end build commands are
documented in `Source Code and Build`.

## Before uploading the ZIP

1. Sign in as `muze233` and confirm that the public profile is available at
   <https://profiles.wordpress.org/muze233/>.
2. Build `npcink-site-toolbox.zip` from the audited commit with
   `composer release:build`, or download the refreshed 3.1.0 release asset after
   its checksum has been updated. Do not zip the Git checkout.
3. Verify the release ZIP and its checksum sidecar:

   ```bash
   composer release:verify -- npcink-site-toolbox.zip
   shasum -a 256 -c npcink-site-toolbox.zip.sha256
   ```

4. Run the final local gates:

   ```bash
   composer test
   composer phpstan
   ```

5. Upload the ZIP at <https://wordpress.org/plugins/developers/add/>. The
   proposed slug is `npcink-site-toolbox`, but the approval email is the source
   of truth for the assigned slug.
6. Whitelist email from `plugins@wordpress.org` and reply in the existing review
   thread if the review team asks questions.

## Listing assets after approval

The repository directory `.wordpress-org/` is a staging mirror for the future
WordPress.org SVN top-level `assets/` directory. It contains:

- `icon-128x128.png` and `icon-256x256.png`
- `banner-772x250.png` and `banner-1544x500.png`
- `screenshot-1.png` through `screenshot-3.png`, matching the numbered captions
  in `readme.txt`

After approval, replace `SVN_SLUG` if the assigned slug differs and inspect all
changes before committing:

```bash
SVN_SLUG=npcink-site-toolbox
SVN_DIR="$HOME/wordpress-org/$SVN_SLUG"
PACKAGE_DIR="$(mktemp -d)"

svn checkout "https://plugins.svn.wordpress.org/$SVN_SLUG/" "$SVN_DIR"
unzip -q npcink-site-toolbox.zip -d "$PACKAGE_DIR"
rsync -a "$PACKAGE_DIR/npcink-site-toolbox/" "$SVN_DIR/trunk/"
rsync -a .wordpress-org/ "$SVN_DIR/assets/"

svn add --force "$SVN_DIR/trunk" "$SVN_DIR/assets"
svn copy "$SVN_DIR/trunk" "$SVN_DIR/tags/3.1.0"
svn status "$SVN_DIR"
svn diff "$SVN_DIR"
```

Only after the status and diff match the reviewed ZIP and listing assets:

```bash
svn commit "$SVN_DIR" -m "Release Npcink Site Toolbox 3.1.0"
```

Do not commit to SVN before the plugin is approved and the assigned slug has
been confirmed.

## Official references

- Plugin submission: <https://developer.wordpress.org/plugins/wordpress-org/planning-submitting-and-maintaining-plugins/>
- Plugin directory upload: <https://wordpress.org/plugins/developers/add/>
- Plugin assets and required sizes: <https://developer.wordpress.org/plugins/wordpress-org/plugin-assets/>
- `readme.txt` format: <https://developer.wordpress.org/plugins/wordpress-org/how-your-readme-txt-works/>
- Detailed plugin guidelines: <https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/>
