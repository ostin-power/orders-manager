import os

def replace_env_variables(template_path, output_path):
    # Read the .env.template file
    with open(template_path, 'r') as file:
        env_template = file.read()

    # Replace placeholders with actual environment variables
    for key, value in os.environ.items():
        placeholder = f"${{{key}}}"
        env_template = env_template.replace(placeholder, value)

    # Write the new .env file
    with open(output_path, 'w') as file:
        file.write(env_template)

    #print(f"Generated {output_path} from {template_path}") #Debug

if __name__ == "__main__":
    # Define paths for the template and output .env file
    template_file = "/var/www/.env.template"
    output_file = "/var/www/.env"

    # Replace environment variables in the template and create the .env file
    replace_env_variables(template_file, output_file)

    # Debug
    #with open(output_file, 'r') as file:
        #print(f"Contents of {output_file}:")
        #print(file.read())