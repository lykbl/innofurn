## What is it?
A little ecommerce pet project to play around with GraphQL, Next.js and websockets

# Linking Sail
Since sail is located in a custom path `bin/sail`, you need to modify your `~/.bashrc` (or something else if you're using a different sh) to something like this:
`alias sail='[ -f sail ] && bash sail || [ -f sail ] && bash vendor/bin/sail || bash bin/sail'`

# Generating Facades Helpers

`sail artisan ide-helper:generate` PHPDocs for facades
`sail artisan ide-helper:models` PHPDocs for models
`sail artisan ide-helper:meta` Add PHPStorm meta


# Creating Patches with Composer

Follow these steps to create patches using Composer for a package:

1. **Create \*.php.old file:**
    - Locate the class you want to modify inside the `vendor/package` directory.
    - Copy the class file and paste it in the same directory with a `.php.old` extension (e.g., `ClassName.php.old`).

2. **Edit \*.php File:**
    - Open the original class file (without the `.old` extension) in a text editor.
    - Make the necessary modifications to the code.

3. **Generate Patch Files:**
    - Run the following command to generate patch files:
      ```bash
      make patch-generate
      ```

4. **Apply Patches:**
    - Run the following command to apply the patches:
      ```bash
      make composer-install
      ```

5. **Confirm Patch Installation:**
    - After applying the patches, confirm the installation was successful by testing the functionality.

6. **Commit Changes to Git:**
    - If the patches work as intended, commit the changes to your Git repository:
      ```bash
      git add patches/your-patch.patch
      git commit -m "Apply patches for custom modifications"
      ```
