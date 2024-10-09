
# Git Strategy for Managing Client-Specific Changes in `property_management` Project

## 1. Set Up a Main Branch for the Core Project

Create a `main` or `master` branch for your project, which will hold the core codebase shared by all clients. This branch will contain general features, improvements, and bug fixes that apply to all customers.

```bash
git checkout -b main
```

## 2. Create Branches for Each Client

For each client that requires custom changes, create a separate branch. This way, you can maintain client-specific code while keeping the core code in `main`.

```bash
git checkout -b client1-main
git checkout -b client2-main
```

These branches will inherit the core code from `main`, and any client-specific changes can be made without affecting other clients.

## 3. Handling Custom Client Changes

When a client requests a change, switch to their specific branch and implement the change there:

```bash
git checkout client1-main
# Make client-specific changes
git commit -m "Client1 specific changes"
```

Repeat this for each client's unique requirements without touching the core codebase.

## 4. Handling Bug Fixes for All Clients

When applying a bug fix that should affect all clients, apply it to the `main` branch first:

```bash
git checkout main
# Apply bug fix
git commit -m "Bug fix for all clients"
git push origin main
```

Then, merge the bug fix into each client branch:

```bash
git checkout client1-main
git merge main
# Resolve any conflicts if necessary
git push origin client1-main

git checkout client2-main
git merge main
# Resolve conflicts
git push origin client2-main
```

This ensures the bug fix is available for all clients, even if they have customized versions of the project.

## 5. Deploying Code to Each Client

When you're ready to deploy, deploy each client-specific branch to their respective environments:

- For `client1`:
    - Deploy `client1-main` branch.
- For `client2`:
    - Deploy `client2-main` branch.

This way, each client gets their custom changes, and bug fixes are included after merging.

## 6. Handling Features/Hotfixes Across Clients

To introduce a new feature or make a hotfix that applies to some clients but not others:

1. Create a feature or hotfix branch off `main`.
2. Once completed, merge it into the appropriate client branches:

```bash
git checkout -b feature-xyz
# Implement feature
git commit -m "New feature"
git push origin feature-xyz

# Merge into specific clients
git checkout client1-main
git merge feature-xyz
git push origin client1-main
```

## 7. Optional: Using Tags for Releases

For production deployments, you can tag specific releases for better version control:

```bash
git tag -a v1.0-client1 -m "Release version 1.0 for client 1"
git push origin v1.0-client1
```

This allows you to track and deploy specific versions of the project for each client.

## Summary:
- **`main`** branch: Core code shared by all clients.
- **`client1-main`, `client2-main`**: Separate branches for each client.
- **Merging**: Bug fixes and general features are applied to `main` and merged into client-specific branches.
- **Deployment**: Deploy from each client’s branch to keep the changes isolated.
