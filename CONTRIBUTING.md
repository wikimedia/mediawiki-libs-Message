# Maintainers guide

## Release process

1. **Update [`HISTORY.md`](./HISTORY.md):**
   Use the `update-history` tool to handle version bumps and release notes:
   ```bash
   bin/update-history [patch|minor|major]
   ```
   This command updates `HISTORY.md` by adding a new version section with the current date.
   Follow the [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) format with labels like "Added," "Changed," or "Fixed." Only include changes that impact end-users. Adhere to [Semantic Versioning](https://semver.org/) for version numbers.
 
2. **Commit changes:** Stage all changes, commit with a message like `Tag vX.Y.Z`, and push for review.

3. **Tag the release:** After merging the commit, pull the latest changes on the `master` branch:
   ```bash
   git checkout main
   git pull origin master
   git tag vX.Y.Z
   git push origin vX.Y.Z
   ```

Remember to, after the commit is merged, first checkout the main branch and pull down the latest changes.
This is to make sure you have the merged version and not the draft commit that you pushed for review.
