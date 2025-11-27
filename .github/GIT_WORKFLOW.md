# 🔀 Git Workflow - SIMKESRA

**Last Updated:** November 27, 2025  
**Repository:** jejakkamera/simkesra
## 📋 Branch Structure

```
main (production)
  ↳ dev (development)
     ↳ feature/* (feature branches)
     ↳ bugfix/* (bug fixes)
     ↳ hotfix/* (urgent fixes)
```

### Branch Descriptions

- **`main`** - Production-ready code, stable releases only
- **`dev`** - Active development, integration branch
- **`feature/*`** - New features (e.g., `feature/user-management`)
- **`bugfix/*`** - Bug fixes (e.g., `bugfix/login-error`)
- **`hotfix/*`** - Critical production fixes (e.g., `hotfix/security-patch`)

---

## 🚀 Current Remote Configuration

```bash
origin      https://git.karawangkab.go.id/dika/setda-simkresa.git
old-origin  https://github.com/jejakkamera/simkesra.git (backup/mirror)
```

### Remote Usage
- **`origin`** - Primary repository (Karawang internal GitLab)
- **`old-origin`** - GitHub backup/mirror

---

## 💻 Development Workflow

### 1. Starting New Feature

```bash
git checkout dev
git pull origin dev

# Create feature branch
git checkout -b feature/nama-fitur

# Work on your feature...
# ... commit changes ...

# Push feature branch
git push origin feature/nama-fitur              # Primary remote
git push old-origin feature/nama-fitur          # Optional backup mirror
```

### 2. Daily Development Work

```bash
git checkout dev
git pull origin dev

# Make changes, then stage & commit
git add .
git commit -m "feat: descriptive message"

# Push changes
git push origin dev
git push old-origin dev
```

### 3. Creating Feature Branch (detailed example)

```bash
git checkout dev
git pull origin dev

git checkout -b feature/payment-gateway

# ... develop feature ...

git add .
git commit -m "feat(payment): add payment gateway integration"

git push origin feature/payment-gateway
git push old-origin feature/payment-gateway
```

### 4. Merging Feature to Dev

```bash
git checkout dev
git pull origin dev

git merge feature/payment-gateway

# Resolve conflicts if needed

git push origin dev
git push old-origin dev

# Cleanup (optional)
git branch -d feature/payment-gateway
git push origin --delete feature/payment-gateway
git push old-origin --delete feature/payment-gateway
```

### 5. Bug Fix Workflow

```bash
git checkout dev
git pull origin dev

git checkout -b bugfix/fix-login-redirect

# ... fix the bug ...

git add .
git commit -m "fix(auth): resolve login redirect issue"

git push origin bugfix/fix-login-redirect
git push old-origin bugfix/fix-login-redirect

git checkout dev
git merge bugfix/fix-login-redirect

git push origin dev
git push old-origin dev

git branch -d bugfix/fix-login-redirect
git push origin --delete bugfix/fix-login-redirect
git push old-origin --delete bugfix/fix-login-redirect
```

### 6. Hotfix for Production (Urgent)

```bash
git checkout main
git pull origin main

git checkout -b hotfix/critical-security-fix

# ... address the production issue ...

git add .
git commit -m "fix(security): patch XSS vulnerability"

git checkout main
git merge hotfix/critical-security-fix
git push origin main
git push old-origin main

git checkout dev
git merge hotfix/critical-security-fix
git push origin dev
git push old-origin dev

git tag -a v1.0.1 -m "Security hotfix"
git push origin v1.0.1
git push old-origin v1.0.1
```

---

## 📝 Commit Message Convention

Use **Conventional Commits** format:

```
<type>(<scope>): <description>

[optional body]

[optional footer]
```

### Types
- **feat**: New feature
- **fix**: Bug fix
- **docs**: Documentation changes
- **style**: Code style changes (formatting, no logic change)
- **refactor**: Code refactoring
- **perf**: Performance improvements
- **test**: Adding or updating tests
- **chore**: Maintenance tasks
- **ci**: CI/CD changes
- **build**: Build system changes

### Examples
```bash
feat(auth): add two-factor authentication
fix(payment): resolve transaction timeout issue
docs(readme): update installation instructions
refactor(user): simplify user validation logic
perf(database): optimize user query with indexes
chore(deps): update Laravel to 11.46.1
```

---

## 🔄 Syncing with Remote

### Pull Latest Changes

```bash
# Pull from dev branch
git checkout dev
git pull origin dev

# If you have local changes, use rebase
git pull --rebase origin dev
```

### Push Your Changes

```bash
# Push current branch
git push origin <branch-name>

# Push all branches
git push origin --all

# Push tags
git push origin --tags

# Mirror (optional)
git push old-origin <branch-name>
```

### Fetch All Branches

```bash
# Fetch all remote branches
git fetch origin

# List all branches
git branch -a
```

---

## 🚢 Release Workflow (Dev → Main)

### Preparing for Release

```bash
# 1. Ensure dev is up to date
git checkout dev
git pull origin dev

# 2. Run all tests
php artisan test
php artisan enlightn

# 3. Update version in relevant files
# Edit: composer.json, package.json, etc.

# 4. Create release commit
git add .
git commit -m "chore(release): prepare v1.1.0"
git push origin dev
git push old-origin dev
```

### Merging to Main (Production)

```bash
# 1. Switch to main
git checkout main
git pull origin main

# 2. Merge dev into main
git merge dev

# 3. Tag the release
git tag -a v1.1.0 -m "Release version 1.1.0"

# 4. Push to production
git push origin main
git push origin v1.1.0
git push old-origin main
git push old-origin v1.1.0

# 5. Create GitHub release (optional)
# Go to: https://github.com/jejakkamera/simkesra/releases/new
```

---

## 🛡️ Branch Protection Rules (Recommended)

### For `main` branch:
- ✅ Require pull request reviews
- ✅ Require status checks to pass
- ✅ No direct pushes (except hotfixes)
- ✅ Require signed commits (optional)

### For `dev` branch:
- ✅ Require pull request for features
- ✅ Allow direct pushes for small changes
- ✅ Require tests to pass

---

## 🔍 Useful Git Commands

### Check Status
```bash
git status                    # Current changes
git log --oneline -10         # Last 10 commits
git log --graph --all         # Visual tree
git branch -a                 # All branches
```

### Undo Changes
```bash
git reset HEAD~1              # Undo last commit (keep changes)
git reset --hard HEAD~1       # Undo last commit (discard changes)
git checkout -- <file>        # Discard file changes
git clean -fd                 # Remove untracked files
```

### Stashing
```bash
git stash                     # Save current changes
git stash list                # List stashes
git stash pop                 # Apply and remove stash
git stash apply               # Apply stash (keep it)
```

### Branch Management
```bash
git branch -d <branch>        # Delete local branch
git push origin --delete <branch>  # Delete remote branch
git branch -m <old> <new>     # Rename branch
```

### Conflict Resolution
```bash
# When merge conflicts occur:
1. Open conflicted files
2. Resolve conflicts (<<<<<<< ======= >>>>>>>)
3. git add <resolved-files>
4. git commit -m "merge: resolve conflicts"
5. git push
```

---

## 📊 Current Branch Status

```
Branch: dev (active development)
   ↳ Tracks all day-to-day work
   ↳ Merge target for feature/bugfix branches

Branch: main (production)
   ↳ Syncs with tested releases from dev
   ↳ Always deploy from this branch
```

---

## 🔒 Security Best Practices

1. **Never commit sensitive data**
   - `.env` files
   - API keys
   - Passwords
   - Private keys

2. **Use .gitignore**
   - Already configured for Laravel
   - Add custom ignores as needed

3. **Review before push**
   ```bash
   git diff                   # Review changes
   git status                 # Check staged files
   git log --oneline -5       # Recent commits
   ```

4. **Sign commits (recommended)**
   ```bash
   git config --global user.signingkey <GPG-KEY>
   git config --global commit.gpgsign true
   ```

---

## 📞 Support

- **Git Documentation:** https://git-scm.com/doc
- **GitHub Guides:** https://guides.github.com
- **Conventional Commits:** https://conventionalcommits.org

---

## ✅ Quick Reference

```bash
# Switch to dev branch
git checkout dev

# Create feature branch
git checkout -b feature/new-feature

# Commit changes
git add .
git commit -m "feat: add new feature"

# Push to primary remote
git push origin <branch-name>

# Mirror when needed
git push old-origin <branch-name>

# Merge back to dev
git checkout dev
git merge feature/new-feature
git push origin dev
git push old-origin dev

# Check status
git status
git log --oneline -5
```

---

**Current Active Branch:** `dev`  
**Production Branch:** `main`  
**Primary Remote:** `origin` (Karawang GitLab)  
**Backup Remote:** `old-origin` (GitHub mirror)
