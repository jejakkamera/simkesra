# 🔀 Git Workflow - SIMKESRA

**Last Updated:** November 27, 2025  
**Repository:** jejakkamera/simkesra

---

## 📋 Branch Structure

```
main (production)
  ↳ developer (development)
     ↳ feature/* (feature branches)
     ↳ bugfix/* (bug fixes)
     ↳ hotfix/* (urgent fixes)
```

### Branch Descriptions

- **`main`** - Production-ready code, stable releases only
- **`developer`** - Active development, integration branch
- **`feature/*`** - New features (e.g., `feature/user-management`)
- **`bugfix/*`** - Bug fixes (e.g., `bugfix/login-error`)
- **`hotfix/*`** - Critical production fixes (e.g., `hotfix/security-patch`)

---

## 🚀 Current Remote Configuration

```bash
origin      git@git.karawangkab.go.id:dika/setda-simkresa.git
old-origin  https://github.com/jejakkamera/simkesra.git (backup/mirror)
```

### Remote Usage
- **`origin`** - Primary repository (Karawang internal GitLab)
- **`old-origin`** - GitHub backup/mirror

---

## 💻 Development Workflow

### 1. Starting New Feature

```bash
# Make sure you're on developer branch
git checkout developer
git pull old-origin developer

# Create feature branch
git checkout -b feature/nama-fitur

# Work on your feature...
# ... commit changes ...

# Push to remote
git push old-origin feature/nama-fitur
```

### 2. Daily Development Work

```bash
# On developer branch
git checkout developer

# Pull latest changes
git pull old-origin developer

# Make changes
# ... edit files ...

# Stage and commit
git add .
git commit -m "feat: descriptive message"

# Push changes
git push old-origin developer
```

### 3. Creating Feature Branch

```bash
# From developer branch
git checkout developer
git pull old-origin developer

# Create and switch to feature branch
git checkout -b feature/payment-gateway

# Work on feature
# ... make changes ...

# Commit regularly
git add .
git commit -m "feat(payment): add payment gateway integration"

# Push feature branch
git push old-origin feature/payment-gateway
```

### 4. Merging Feature to Developer

```bash
# Update developer branch first
git checkout developer
git pull old-origin developer

# Merge feature branch
git merge feature/payment-gateway

# Resolve conflicts if any
# ... fix conflicts ...

# Push merged changes
git push old-origin developer

# Delete feature branch (optional)
git branch -d feature/payment-gateway
git push old-origin --delete feature/payment-gateway
```

### 5. Bug Fix Workflow

```bash
# From developer branch
git checkout developer
git checkout -b bugfix/fix-login-redirect

# Fix the bug
# ... make changes ...

# Commit fix
git add .
git commit -m "fix(auth): resolve login redirect issue"

# Push bugfix
git push old-origin bugfix/fix-login-redirect

# Merge to developer
git checkout developer
git merge bugfix/fix-login-redirect
git push old-origin developer
```

### 6. Hotfix for Production (Urgent)

```bash
# From main branch
git checkout main
git pull old-origin main

# Create hotfix branch
git checkout -b hotfix/critical-security-fix

# Fix the issue
# ... make changes ...

# Commit
git add .
git commit -m "fix(security): patch XSS vulnerability"

# Merge to main
git checkout main
git merge hotfix/critical-security-fix
git push old-origin main

# Also merge to developer
git checkout developer
git merge hotfix/critical-security-fix
git push old-origin developer

# Tag the release
git tag -a v1.0.1 -m "Security hotfix"
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
# Pull from developer branch
git checkout developer
git pull old-origin developer

# If you have local changes, use rebase
git pull --rebase old-origin developer
```

### Push Your Changes

```bash
# Push current branch
git push old-origin <branch-name>

# Push all branches
git push old-origin --all

# Push tags
git push old-origin --tags
```

### Fetch All Branches

```bash
# Fetch all remote branches
git fetch old-origin

# List all branches
git branch -a
```

---

## 🚢 Release Workflow (Developer → Main)

### Preparing for Release

```bash
# 1. Ensure developer is up to date
git checkout developer
git pull old-origin developer

# 2. Run all tests
php artisan test
php artisan enlightn

# 3. Update version in relevant files
# Edit: composer.json, package.json, etc.

# 4. Create release commit
git add .
git commit -m "chore(release): prepare v1.1.0"
git push old-origin developer
```

### Merging to Main (Production)

```bash
# 1. Switch to main
git checkout main
git pull old-origin main

# 2. Merge developer into main
git merge developer

# 3. Tag the release
git tag -a v1.1.0 -m "Release version 1.1.0"

# 4. Push to production
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

### For `developer` branch:
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
Branch: developer (active development)
  ↳ Last commit: 2a960d3 "ok"
  ↳ Commits ahead of main: 0
  ↳ Status: Clean, up to date

Branch: main (production)
  ↳ Last commit: 2a960d3 "ok"
  ↳ Status: Stable, production-ready
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
# Switch to developer branch
git checkout developer

# Create feature branch
git checkout -b feature/new-feature

# Commit changes
git add .
git commit -m "feat: add new feature"

# Push to remote
git push old-origin <branch-name>

# Merge to developer
git checkout developer
git merge feature/new-feature
git push old-origin developer

# Check status
git status
git log --oneline -5
```

---

**Current Active Branch:** `developer`  
**Production Branch:** `main`  
**Remote:** `old-origin` (GitHub backup)
