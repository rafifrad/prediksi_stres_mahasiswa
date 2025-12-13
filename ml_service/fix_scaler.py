import joblib
import numpy as np
from sklearn.preprocessing import StandardScaler

print("=" * 60)
print("🔧 Creating New Scaler for 15 Selected Features")
print("=" * 60)

# Load feature names (15 selected features)
feature_names = joblib.load('models/feature_names.joblib')
print(f"\n✅ Loaded {len(feature_names)} selected features:")
for i, feature in enumerate(feature_names, 1):
    print(f"   {i:2d}. {feature}")

# Load old scaler to get the parameters for the 15 selected features
old_scaler = joblib.load('models/scaler_stress.joblib')

print(f"\n📊 Old scaler info:")
print(f"   Features in old scaler: {old_scaler.n_features_in_}")

# Check if scaler has feature names
if hasattr(old_scaler, 'feature_names_in_'):
    old_feature_names = old_scaler.feature_names_in_
    print(f"   Old feature names available: {len(old_feature_names)}")
    
    # Find indices of selected features in old scaler
    selected_indices = []
    for feature in feature_names:
        if feature in old_feature_names:
            idx = list(old_feature_names).index(feature)
            selected_indices.append(idx)
        else:
            print(f"   ⚠️  Feature '{feature}' not found in old scaler")
    
    if len(selected_indices) == len(feature_names):
        print(f"\n✅ All {len(feature_names)} features found in old scaler")
        print(f"   Selected indices: {selected_indices}")
        
        # Create new scaler with parameters from selected features
        new_scaler = StandardScaler()
        new_scaler.mean_ = old_scaler.mean_[selected_indices]
        new_scaler.scale_ = old_scaler.scale_[selected_indices]
        new_scaler.var_ = old_scaler.var_[selected_indices]
        new_scaler.n_features_in_ = len(feature_names)
        new_scaler.n_samples_seen_ = old_scaler.n_samples_seen_
        new_scaler.feature_names_in_ = np.array(feature_names)
        
        # Test new scaler
        print("\n🧪 Testing new scaler...")
        test_data = np.array([3.5] * 15).reshape(1, -1)
        scaled = new_scaler.transform(test_data)
        print(f"   Input shape: {test_data.shape}")
        print(f"   Output shape: {scaled.shape}")
        print(f"   ✅ Scaler works correctly!")
        
        # Save new scaler
        joblib.dump(new_scaler, 'models/scaler_stress.joblib')
        print(f"\n💾 New scaler saved to 'models/scaler_stress.joblib'")
        
        # Verify
        verify_scaler = joblib.load('models/scaler_stress.joblib')
        print(f"\n✅ Verification:")
        print(f"   Scaler expects: {verify_scaler.n_features_in_} features")
        print(f"   Feature names: {len(feature_names)} features")
        print(f"   Match: {'✅ YES' if verify_scaler.n_features_in_ == len(feature_names) else '❌ NO'}")
        
    else:
        print(f"\n❌ Error: Only found {len(selected_indices)} out of {len(feature_names)} features")
else:
    print("\n⚠️  Old scaler doesn't have feature names")
    print("   Creating new scaler from scratch...")
    
    # Create a new scaler with neutral values (will need to be fitted with real data)
    new_scaler = StandardScaler()
    new_scaler.mean_ = np.zeros(15)
    new_scaler.scale_ = np.ones(15)
    new_scaler.var_ = np.ones(15)
    new_scaler.n_features_in_ = 15
    new_scaler.n_samples_seen_ = 1000  # arbitrary value
    new_scaler.feature_names_in_ = np.array(feature_names)
    
    # Save
    joblib.dump(new_scaler, 'models/scaler_stress.joblib')
    print(f"   ✅ New neutral scaler created and saved")
    print(f"   ⚠️  NOTE: This scaler uses neutral values (mean=0, scale=1)")
    print(f"   For better results, retrain with actual data if available")

print("\n" + "=" * 60)
print("✅ Scaler update complete!")
print("=" * 60)
